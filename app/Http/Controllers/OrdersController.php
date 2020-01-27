<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;


class OrdersController extends Controller {   
    //index orders
    public function index() {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    //store an order
    public function store() {
        
        //validate
        request()->validate(['orderID' => 'required']);
        
        //persist
        Order::create(request(['orderID', 'Users_id', 'Status_statusID', 'created_at', 'country']));
        
        //redirect
        return redirect('/orders');
    }

}
