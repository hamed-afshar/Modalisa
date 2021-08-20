<?php

namespace App\Http\Controllers\API;

use App\Cost;
use App\Exceptions\ViewHistoryDenied;
use App\Exceptions\WrongProduct;
use App\History;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\KargoResource;
use App\Http\Resources\NoteResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Kargo;
use App\Note;
use App\Order;
use App\Product;
use App\Traits\ImageTrait;
use App\Traits\KargoTrait;
use App\Transaction;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use mysql_xdevapi\Table;

class AdminController extends Controller
{
    use KargoTrait, ImageTrait;

    /**
     * index all costs for all users
     * super privilege users are able to see all costs created for any retailer
     * @return mixed
     * @throws AuthorizationException
     */
    public function indexCosts()
    {
        $this->authorize('indexCosts', Admin::class);
        $costs = Cost::all();
        return response(['costs' => CostResource::collection($costs), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * show a single cost for the given user
     * super privilege users are able to see a single cost for a specific user
     * @param Cost $cost
     * @return mixed
     * @throws AuthorizationException
     */
    public function showCost(Cost $cost)
    {
        $this->authorize('indexSingleCost', Admin::class);
        $cost = Cost::with(['images'])->where('id' , '=', $cost->id)->get();
        return response(['cost' => new CostResource($cost), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * Determine whether admin can create cost for the given user
     * @param Request $request
     * @param User $user
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function storeCost(Request $request, User $user)
    {
        $this->authorize('createCost', Admin::class);
        // first cost record must be created and get cost_id to be used in image creation model

        // prepare cost's data to create record in db
        $request->validate([
            'amount' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'costable_type' => 'required',
            'costable_id' => 'required'
        ]);
        $costData = [
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'costable_type' => $request->input('costable_type'),
            'costable_id' => $request->input('costable_id')
        ];
        // create a cost record for the given user
        $cost = $user->costs()->create($costData);
        // if image is included, then image should be uploaded and associated record will be created in db
        if ($request->has('image')) {
            // first upload image
            $image = $request->file('image');
            $imageNewName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageNewName);
            // create record for the uploaded image
            $imageData = [
                // imagable_type always remains App\Cost
                'imagable_type' => 'App\Cost',
                'imagable_id' => $cost->id,
                'image_name' => $filePath
            ];
            $user->images()->create($imageData);
        }
        return response(['cost' => new CostResource($cost), 'message' => trans('translate.cost_created')], 200);
    }

    /**
     * index all cost for a specific model
     * only SuperPrivilege users are allowed
     * @throws AuthorizationException
     */
    public function indexCostModel($id, $model)
    {
        $this->authorize('indexSingleCost', Admin::class);
        $costs = Cost::with('images')->where(['costable_type' => $model, 'costable_id' => $id])->get();
        return response(['costs' => CostResource::collection($costs), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * update a cost record
     * only SuperPrivilege users are allowed
     * @param Request $request
     * @param Cost $cost
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function updateCost(Request $request, Cost $cost)
    {
        $this->authorize('updateCost', Admin::class);
        $request->validate([
            'amount' => 'required',
            'description' => 'required',
            'costable_type' => 'required',
            'costable_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $costData = [
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'costable_type' => $request->input('costable_type'),
            'costable_id' => $request->input('costable_id')
        ];
        //update the cost record
        $cost->update($costData);
        // if request has image for update then new image name will be generated and old image will be deleted
        // if request does not have image, then image will not change
        if ($request->has('image')) {
            $oldImage = $cost->images()
                ->where('imagable_id', $cost->id)
                ->where('imagable_type', 'App\Cost');
            $oldImageName = $oldImage->value('image_name');
            $image = $request->file('image');
            $imageNewName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageNewName);
            $this->deleteOne('public', [$oldImageName]);
            $imageData = [
                // imagable_type always remains App\\Cost
                'imagable_type' => 'App\Cost',
                'imagable_id' => $cost->id,
                'image_name' => $filePath
            ];
            // update image record for the given user
            $oldImage->update($imageData);
        }
        return response(['message' => trans('translate.cost_updated')], 200);
    }

    public function deleteCost(Cost $cost)
    {
        $this->authorize('deleteCost', Admin::class);
        $imageNameArray = $cost->images()->where('imagable_id', $cost->id)->pluck('image_name');
        DB::transaction(function () use ($cost, $imageNameArray) {
            //delete the cost's image file from directory
            $this->deleteOne('public', $imageNameArray);
            //delete the cost image records
            $cost->images()->delete();
            //delete the given cost records
            $cost->delete();
        }, 1);
        return response(['message' => trans('translate.cost_deleted')], 200);
    }

    /**
     * create kargo for the given user
     * super privilege users are able to create kargo for the given user
     * @param Request $request
     * @param User $user
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */

    public function storeKargo(Request $request, User $user)
    {
        $this->authorize('createKargo', Admin::class);
        $request->validate([
            'receiver_name' => 'required',
            'receiver_tel' => 'required',
            'receiver_address' => 'required',
            'sending_date' => 'required | date_format:Y-m-d',
            'kargo_list' => 'required'
        ]);
        $kargoData = [
            'receiver_name' => $request->input('receiver_name'),
            'receiver_tel' => $request->input('receiver_tel'),
            'receiver_address' => $request->input('receiver_address'),
            'sending_date' => $request->input('sending_date')
        ];
        $kargoList = $request->input('kargo_list');
        $this->createKargo($user, $kargoData, $kargoList);
        return response(['message' => trans('translate.kargo_created')], 200);

    }


    /**
     * index all kargos
     * super privilege users are able to see all kargos with related user
     * @throws AuthorizationException
     */
    public function indexKargos()
    {
        $this->authorize('indexKargos', Admin::class);
        $kargos = Kargo::with('user', 'products')->get();
        return response(['kargos' => KargoResource::collection($kargos), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * show a single kargo
     * super privilege users are able to see all kargos with all related user and products
     * @param Kargo $kargo
     * @return Application|Response|ResponseFactory
     * @throws AuthorizationException
     */
    public function showKargo(Kargo $kargo)
    {
        $this->authorize('indexSingleKargo', Admin::class);
        $kargos = $kargo->with('user', 'products')->get();
        return response(['kargos' => KargoResource::collection($kargos), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * confirm the given kargo
     * only super privilege users can confirm kargos
     * @param Request $request
     * @param Kargo $kargo
     * @return string
     * @throws AuthorizationException
     * @throws ImageIsRequired
     */
    public function confirmKargo(Request $request, Kargo $kargo)
    {
        $this->authorize('confirmKargo', Admin::class);
        // kargo will be confirmed for this user
        $user = $kargo->user;
        // first upload the kargo, then upload the image
        $request->validate([
            'weight' => 'required',
            'confirmed' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $data = [
            'weight' => $request->input('weight'),
            'confirmed' => $request->input('confirmed'),
        ];
        $kargo->update($data);
        // upload image for the kargo, if request doest not contain image then throw an exception
        $image = $request->file('image');
        $imageName = date('mdYHis') . uniqid();
        $folder = '/images/';
        $filePath = $folder . $imageName . '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $imageName);
        // create record for the uploaded image
        $imageData = [
            // imagable_type always remains App\Kargo
            'imagable_type' => 'App\Kargo',
            'imagable_id' => $kargo->id,
            'image_name' => $filePath
        ];
        $user->images()->create($imageData);
        return response(['message' => trans('translate.confirm_kargo')], 200);
    }

    /**
     * update the kargo record for the given user
     * super privilege users are able to update both confirmed and not confirmed kargos
     * @param Request $request
     * @param User $user
     * @param Kargo $kargo
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function updateKargo(Request $request, User $user, Kargo $kargo)
    {
        $this->authorize('updateKargo', Admin::class);
        $request->validate([
            'receiver_name' => 'required',
            'receiver_tel' => 'required',
            'receiver_address' => 'required',
            'sending_date' => 'required'
        ]);
        $kargoData = [
            'receiver_name' => $request->input('receiver_name'),
            'receiver_tel' => $request->input('receiver_tel'),
            'receiver_address' => $request->input('receiver_address'),
            'sending_date' => $request->input('sending_date')
        ];
        $kargo->update($kargoData);
        return response(['message' => 'kargo updated'], 200);
    }

    /**
     * delete the kargo for the given user
     * super privilege users are able to delete both confirmed and not confirmed kargos
     * @param User $user
     * @param Kargo $kargo
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function deleteKargo(User $user, Kargo $kargo)
    {
        $this->authorize('deleteKargo', Admin::class);
        $imageNameArray = $kargo->images()->where('imagable_id', $kargo->id)->pluck('image_name');
        DB::transaction(function () use ($kargo, $imageNameArray) {
            //delete the kargo's image file from directory
            $this->deleteOne('public', $imageNameArray);
            //delete the kargo image records
            $kargo->images()->delete();
            //delete the given kargo record
            $kargo->delete();
        }, 1);
        return response(['message' => trans('translate.deleted')]);
    }

    /**
     * add items to the kargo
     * super privilege users are able to add items to the kargo
     * @param User $user
     * @param Kargo $kargo
     * @param Product $product
     * @return string
     * @throws AuthorizationException
     * @throws WrongProduct
     */
    public function addToKargo(User $user, Kargo $kargo, Product $product)
    {
        $this->authorize('updateKargo', Admin::class);
        if ($product->user()->id != $user->id) {
            throw new WrongProduct();
        } else {
            $kargo->products()->save($product);
            $kargo->refresh();
        }
        return response(['kargo' => new KargoResource($kargo->with('products')->where('id', '=', $kargo->id)->get()), 'message' => trans('translate.added_to_kargo')], 200);
    }

    public function removeFromKargo(Kargo $kargo, Product $product)
    {
        $this->authorize('updateKargo', Admin::class);
        $kargo->products()->where('id', '=', $product->id)->delete($product);
        $kargo->refresh();
        return response(['kargo' => new KargoResource($kargo->with('products')->where('id', '=', $kargo->id)->get()), 'message' => trans('translate.remove_from_kargo')], 200);

    }

    /**
     * check to see whether products has binded to any kargo or not
     * users should have see-kargos permission to be allowed
     * key parameter will determine to check for null or not-null kargo fields
     */
    public function kargoAssignment($key)
    {
        $this->authorize('indexOrder', Admin::class);
        if ($key == 1) {
            $condition = !null;
        }
        if ($key == 0) {
            $condition = null;
        }
        $products = Product::where('kargo_id', $condition)->get();
        return response(['products' => new ProductResource($products), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * index all notes related to a model
     */
    public function indexNotes($id, $model)
    {
        $this->authorize('indexNotes', Admin::class);
        $notes = Note::where(['notable_type' => $model, 'notable_id' => $id])->get();
        return response(['notes' => NoteResource::collection($notes), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * index all histories related to a products
     */
    public function indexHistories(Product $product)
    {
        $this->authorize('indexHistories', Admin::class);
        $histories = $product->histories()->with('status')->get();
        return response(['histories' => HistoryResource::collection($histories), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * confirm transactions
     * only SystemAdmin can confirm transactions
     * @param Transaction $transaction
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function confirmTransaction(Transaction $transaction)
    {
        $this->authorize('confirmTransaction', Admin::class);
        $transaction->update(['confirmed' => 1]);
        return response(['message' => trans('translate.transaction_confirmed')], 200);
    }

    /**
     * index all orders with relative products
     * Super privilege users can index all orders
     * @throws AuthorizationException
     */
    public function indexOrders()
    {
        $this->authorize('indexOrder', Admin::class);
        $orders = Order::with(['products.images'])->get();
        return response(['orders' => OrderResource::collection($orders), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * index a single order with relative products
     * Super privilege users can index orders with relative product
     * @param Order $order
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function indexSingleOrder(Order $order)
    {
        $this->authorize('indexSingleOrder', Admin::class);
        $order = $order->with('products.images')
            ->where('id', '=', $order->id)
            ->get();
        return response(['order' => new OrderResource($order), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * update weight filed for a product
     * Super Privilege users can update this fields
     * @throws AuthorizationException
     */
    public function updateWeight(Request $request, Product $product)
    {
        $this->authorize('updateWeight', Admin::class);
        $request->validate([
            'weight' => 'required | numeric'
        ]);
        $productData = [
          'weight' => $request->input('weight')
        ];
        $product->update($productData);
        return response(['product' => new ProductResource($product), 'message' => trans('translate.product_updated')], 200);
    }

    /**
     * update weight filed for a product
     * Super Privilege users can update this fields
     * @throws AuthorizationException
     */
    public function updateRef(Request $request, Product $product)
    {
        $this->authorize('updateWeight', Admin::class);
        $request->validate([
            'ref' => 'required'
        ]);
        $productData = [
            'ref' => $request->input('ref')
        ];
        $product->update($productData);
        return response(['product' => new ProductResource($product), 'message' => trans('translate.product_updated')], 200);
    }
}
