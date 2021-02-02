<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Helper\StatusManager;
use App\Order;
use App\Product;
use App\Traits\ImageTrait;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use  ImageTrait;

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
     * function to return the current status
     * @param Product $product
     * @return int
     */
    public function getStatus(Product $product):int
    {
        $latestHistory = DB::table('histories')
            ->where('product_id', '=' , $product->id)
            ->orderBy('created_at', 'desc')->first();
        return $latestHistory->status_id;
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
        return Auth::user()->orders()->with(['products', 'customer'])->get();
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
     * all order by default consider retailers as the customer
     * but retailer can change this customer later
     * @param Request $request
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
        //first create record for the order then add products
        //create record for the order
        $order = $user->orders()->create();
        //create record for the product
        $product = $order->products()->create($productData);
        //upload image for the created product
        $image = $request->file('image');
        $this->uploadImage($user, $product, $image);
    }

    /**
     * assign the customer to the given order
     * users should have create-orders permission to be allowed
     * @param Customer $customer
     * @param Order $order
     * @throws AuthorizationException
     */
    public function assignCustomer(Customer $customer, Order $order)
    {
        $this->authorize('create', Order::class);
        $order->update(['customer_id' => $customer->id]);
    }

    /**
     * add product to the given order
     * users should have create-orders permission to be allowed
     * @param Request $request
     * @param Order $order
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
        $image = $request->file('image');
        $this->uploadImage($user, $product, $image);
    }

    /**
     * remove product from the given order
     * users should have create-orders permission to be allowed
     * if order contains just one product then order record must be deleted completely
     * if order contains more than one product, then just the given product will be deleted
     * it is not possible to delete products if they have
     * status will change from current status to deleted status:0
     * @param Product $product
     * @throws AuthorizationException
     */
    public function deleteProduct(Product $product)
    {
        $this->authorize('create', Order::class);
        //nextStatus will be Order Deleted
        $nextStatus = 0;
        $currentStatus = $this->getStatus($product);
        $statusManager = new StatusManager($product, $currentStatus, $nextStatus);
        //validate this change from current to deleted status
        if($statusManager->check()) {
            $orderID = $product->order()->value('id');
            $order = Order::find($orderID);
            $productCount = $order->products()->count();
            // if order contains less than 1 product, then whole record for this order will be deleted
            // else if order contains more than 1 product, then only the given product will be deleted
            if($productCount <= 1) {
                $order->delete();
            } else {
                $order->products()->delete($product);
            }
        } else {
            abort(403, 'Access Denied');
        }
    }

    /**
     * @test
     * edit the given product
     * users can only edit products that has not been bought yet
     * users should have create-orders permission to be allowed
     * status will change from current status to Order Edit status:9
     * @param Request $request
     * @param Product $product
     * @throws AuthorizationException
     */
    public function editProduct(Request $request, Product $product)
    {
        $this->authorize('create', Order::class);
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
        $nextStatus = 9;
        $currentStatus = $this->getStatus($product);
        $statusManager = new StatusManager($product, $currentStatus, $nextStatus);
        //validate this change from current to Order Edited status
        if($statusManager->check()) {
            $product->update($productData);
            $statusManager->changeHistory();
            if($request->has('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                $image = $request->file('image');
                $this->uploadImage($user, $product, $image);
            }
        } else {
            abort(403, 'Access Denied');
        }
        dd($this->getStatus($product));
    }
}
