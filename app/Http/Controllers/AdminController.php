<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Cost;
use App\Kargo;
use App\Product;
use App\Traits\ImageTrait;
use App\Traits\KargoTrait;
use App\Transaction;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


class AdminController extends Controller

{
    use KargoTrait, ImageTrait;

    /**
     * index costs for the given user
     * super privilege users are able to see all costs created for any retailer
     * @param User $user
     * @return mixed
     * @throws AuthorizationException
     */
    public function indexCosts(User $user)
    {
        $this->authorize('indexCosts', Admin::class);
        return $user->costs;
    }

    /**
     * show a single cost for the given user
     * super privilege users are able to see a single cost for a specific user
     * @param User $user
     * @param Cost $cost
     * @return mixed
     * @throws AuthorizationException
     */
    public function showCost(User $user, Cost $cost)
    {
        $this->authorize('indexSingleCost', Admin::class);
        return $user->costs;
    }

    /**
     * Determine whether admin can create cost for the given user
     * @param Request $request
     * @throws AuthorizationException
     */
    public function storeCost(Request $request)
    {
        $this->authorize('createCost', Admin::class);
        // first cost record must be created and get cost_id to be used in image creation model
        // cost will be created for this user
        $user = $request->input('user');
        // prepare cost's data to create record in db
        $request->validate([
            'user' => 'required',
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
    }

    /**
     * update a cost record
     * only SuperPrivilege users are allowed
     * @param Request $request
     * @param Cost $cost
     * @throws AuthorizationException
     */
    public function updateCost(Request $request, Cost $cost)
    {
        $this->authorize('updateCost', Admin::class);
        $request->validate([
            'user' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $user = $request->input('user');
        $costData = [
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
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
    }

    /**
     * create kargo for the given user
     * super privilege users are able to create kargo for the given user
     * @param Request $request
     * @param User $user
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
    }


    /**
     * index all kargos
     * super privilege users are able to see all kargos with related user
     * @throws AuthorizationException
     */
    public function indexKargos()
    {
        $this->authorize('indexKargos', Admin::class);
        return Kargo::with(['user'])->get();
    }

    /**
     * show a single kargo
     * super privilege users are able to see all kargos with all related user and products
     * @throws AuthorizationException
     */
    public function showKargo()
    {
        $this->authorize('indexSingleKargo', Admin::class);
        return Kargo::with(['user', 'products'])->get();
    }

    /**
     * confirm the given kargo
     * only super privilege users can confirm kargos
     * @param Request $request
     * @param Kargo $kargo
     * @return string
     * @throws AuthorizationException
     */
    public function confirmKargo(Request $request, Kargo $kargo)
    {
        $this->authorize('confirm', Admin::class);
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
        // upload image for the kargo
        $image = $request->file('image');
        $imageNewName = date('mdYHis') . uniqid();
        $folder = '/images/';
        $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $imageNewName);
        // create record for the uploaded image
        $imageData = [
            // imagable_type always remains App\Kargo
            'imagable_type' => 'App\Kargo',
            'imagable_id' => $kargo->id,
            'image_name' => $filePath
        ];
        $user->images()->create($imageData);
    }

    /**
     * update the kargo record for the given user
     * super privilege users are able to update both confirmed and not confirmed kargos
     * @param Request $request
     * @param User $user
     * @param Kargo $kargo
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
        $user->kargos()->update($kargoData);
    }

    /**
     * delete the kargo for the given user
     * super privilege users are able to delete both confirmed and not confirmed kargos
     * @param User $user
     * @param Kargo $kargo
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
    }

    /**
     * add items to the kargo
     * super privilege users are able to add items to the kargo
     * @param User $user
     * @param Kargo $kargo
     * @param Product $product
     * @return string
     * @throws AuthorizationException
     */
    public function addToKargo(User $user, Kargo $kargo, Product $product)
    {
        $this->authorize('updateKargo', Admin::class);
        if ($product->user()->value('id') != $user->id) {
            return Redirect::back()->withErrors('msg', trans('translate.wrong_kargo_add'));
        } else {
            $kargo->products()->save($product);
            $kargo->refresh();
        }
    }

    public function removeFromKargo(Kargo $kargo, Product $product)
    {
        $this->authorize('updateKargo', Admin::class);
        $kargo->products()->delete($product);
        $kargo->refresh();
    }

    /**
     * confirm transactions
     * only SystemAdmin can confirm transactions
     * @param Transaction $transaction
     * @throws AuthorizationException
     */
    public function confirmTransaction(Transaction $transaction)
    {
        $this->authorize('confirm', Admin::class);
        $transaction->update(['confirmed' => 1]);
    }
}
