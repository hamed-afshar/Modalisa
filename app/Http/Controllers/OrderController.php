<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

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

    /** store orders
     * all related products
     * @param Request $request
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);
        $user = Auth::user();
        $productList = array();
        //first create order then add all products
        $request->validate([
            'customer_id' => 'required',
            'productList' => 'required'
        ]);
        $products = $request->input('productList');
        foreach ($products as $item) {
            $product = Product::find($item);
            $productList[] = $product;
        }
        $orderData = [
            'customer_id' => $request->input('customer_id'),
        ];
        DB::beginTransaction();
        $order = $user->orders()->create($orderData);
        dd('order controller');
//        $order->products()->createMany($productList);
    }

}
