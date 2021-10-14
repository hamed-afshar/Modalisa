<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Exceptions\ProductDeleteNotAllowed;
use App\Exceptions\ProductEditNotAllowed;
use App\Helper\StatusManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UpdatedProductResource;
use App\Order;
use App\Product;
use App\Traits\HistoryTrait;
use App\Traits\ImageTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Collection;

class OrderController extends Controller
{
    use  ImageTrait, HistoryTrait;

    /**
     * function to upload images
     * @param $user
     * @param $product
     * @param $image
     */
    public function uploadImage($user, $product, $image)
    {
        $imageName = date('mdYHis') . uniqid();
        $folder = '/images/';
        $filePath = $folder . $imageName . '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $imageName);
        $imageData = [
            //imagable_type always remains App\Product
            'imagable_type' => 'App\Product',
            'imagable_id' => $product->id,
            'image_name' => $filePath
        ];
        //create record for the image
        $user->images()->create($imageData);
    }

    /**
     * index all orders with related products and customers
     * users with see-orders permission are allowed
     * users can only see their own records
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Order::class);
        $orders = Auth::user()->orders()->with([
                'products' => function ($query1) {
                    $query1->with([
                            'images',
                            'histories.status'])->orderBy('id', 'desc')->get();
                },
                'customer',
            ]
        )->orderBy('id', 'desc')->get();
        return response(['orders' => OrderResource::collection($orders), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * form to create order
     * VueJs modal generates this form
     * only users with create-orders permission are allowed
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Order::class);
    }

    /**
     * store orders
     * users should have create-orders permission to be allowed
     * all order by default set retailers as default customer
     * but retailer can change this customer later
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);
        $user = Auth::user();
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'size' => 'required',
            'color' => 'required',
            'link' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'country' => 'required',
            'currency' => 'required',
            'customer_id' => 'required'
        ]);

        $productData = [
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'link' => $request->input('link'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'country' => $request->input('country'),
            'currency' => $request->input('currency'),

        ];
        $customerData = [
            'customer_id' => $request->input('customer_id')
        ];
        //first create record for the order then add products
        //create record for the order
        $order = $user->orders()->create($customerData);
        //create record for the product
        $product = $order->products()->create($productData);
        //upload image for the created product and create a record in the images table
        $image = $request->file('image');
        $this->uploadImage($user, $product, $image);
        $orderResult = Order::find($order->id)->with(['products'])->get();
        return response(['order' => new OrderResource($orderResult),'message' => trans('translate.order_saved')], 200);
    }

    /**
     * show a single product
     * users should have see-orders permission to be allowed
     * @param Product $product
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function showProduct(Product $product)
    {
        $order = $product->order;
        $this->authorize('view', $order);
        $result = $product->with(['images'])
            ->where('id', '=', $product->id)
            ->get();
        return response(['product' => new ProductResource($result), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * assign a customer to the given order
     * users should have create-orders permission to be allowed
     * @param Customer $customer
     * @param Order $order
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function assignCustomer(Customer $customer, Order $order)
    {
        $this->authorize('create', Order::class);
        $order->update(['customer_id' => $customer->id]);
        return response(['message' => trans('translate.customer_assigned')], 200);
    }

    /**
     * add product to the given order
     * users should have create-orders permission to be allowed
     * @param Request $request
     * @param Order $order
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function addToOrder(Request $request, Order $order)
    {
        $this->authorize('create', Order::class);
        $user = Auth::user();
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'size' => 'required',
            'color' => 'required',
            'link' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'country' => 'required',
            'currency' => 'required',
        ]);
        $productData = [
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'link' => $request->input('link'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'country' => $request->input('country'),
            'currency' => $request->input('currency'),
        ];
        $product = $order->products()->create($productData);
        $product->refresh();
        $image = $request->file('image');
        $this->uploadImage($user, $product, $image);
        return response(['message' => trans('translate.product_added')], 200);
    }

    /**
     * remove product from the given order
     * users should have create-orders permission to be allowed
     * if order contains just one product then order record must be deleted completely
     * if order contains more than one product, then just the given product will be deleted
     * it is not possible to delete products if they have bought
     * status will change from current status to deleted status:0
     * @param Product $product
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws ProductDeleteNotAllowed
     */
    public function deleteProduct(Product $product)
    {
        $order = $product->order;
        $this->authorize('delete', $order);
        //nextStatus will be Order Deleted
        $nextStatus = 1;
        $currentStatus = $this->getStatus($product);
        $statusManager = new StatusManager($product, $currentStatus, $nextStatus);
        //validate this change from current to deleted status
        if ($statusManager->check()) {
            $orderID = $product->order()->value('id');
            $order = Order::find($orderID);
            $productCount = $order->products()->count();
            $oldImage = $product->images()
                ->where('imagable_id', $product->id)
                ->where('imagable_type', 'App\Product');
            $oldImageName = $oldImage->value('image_name');
            // if order contains less than 1 product, then whole record for this order will be deleted
            // else if order contains more than 1 product, then only the given product will be deleted
            // at the same time all respective image records and image files must be deleted
            if ($productCount <= 1) {
                $product->images()->delete($oldImage);
                $order->delete();
                $this->deleteOne('public', [$oldImageName]);
                return response(['message' => trans('translate.product_deleted')]);
            } else {
                $product->images()->delete($oldImage);
                $order->products()->where('id', '=', $product->id)->delete();
                $this->deleteOne('public', [$oldImageName]);
                return response(['message' => trans('translate.product_deleted')], 200);
            }
        } else {
            throw new ProductDeleteNotAllowed();
        }
    }

    /**
     * @test
     * edit the given product
     * users can only edit products that has not been bought yet
     * users should have create-orders permission to be allowed
     * status will change from current status to Order Edit status:10
     * @param Request $request
     * @param Product $product
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws ProductEditNotAllowed
     */
    public function editProduct(Request $request, Product $product)
    {
        $order = $product->order;
        $this->authorize('update', $order);
        $user = Auth::user();
        $request->validate([
            'size' => 'required',
            'color' => 'required',
            'link' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'country' => 'required',
            'currency' => 'required',
        ]);
        $productData = [
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'link' => $request->input('link'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'country' => $request->input('country'),
            'currency' => $request->input('currency'),
        ];
        //next status will be Order Edited
        $nextStatus = 10;
        $currentStatus = $this->getStatus($product);
        $statusManager = new StatusManager($product, $currentStatus, $nextStatus);
        //validate this change from current to Order Edited status
        if ($statusManager->check()) {
            $product->update($productData);
            $statusManager->changeHistory();
            if ($request->has('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                $oldImage = $product->images()
                    ->where('imagable_id', $product->id)
                    ->where('imagable_type', 'App\Product');
                $oldImageName = $oldImage->value('image_name');
                $image = $request->file('image');
                $product->images()->delete($oldImage);
                $this->deleteOne('public', [$oldImageName]);
                $this->uploadImage($user, $product, $image);
            }
            $result = Product::with([
                'images',
                'histories' => function($query) {
                    $query->with('status')
                        ->latest()
                        ->first();
            }])->where('id', '=', $product->id)->get();
            return response(['product' => new ProductResource($result), 'message' => trans('translate.product_updated')], 200);
        } else {
            throw new ProductEditNotAllowed();
        }
    }
}
