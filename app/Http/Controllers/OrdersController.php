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
        $attributes = request()->validate([
            'orderID' => 'required',
            'Users_id' => 'required',
            'Status_statusID' => 'required',
            'created_at' => 'required',
            'country' => 'required'
            ]);
        
        //persist
        Order::create($attributes);
        
        //redirect
        return redirect('/orders');
    }
    
    public function show(Order $order) 
    {
        return view('orders.show', compact('order'));
    }
}
