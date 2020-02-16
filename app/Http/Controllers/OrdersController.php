<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;


class OrdersController extends Controller {   
    //index orders
    public function index() {
        $orders = auth()->user()->orders;
        return view('orders.index', compact('orders'));
    }

    //store an order
    public function store() {
               
        //validate and persist
        auth()->user()->orders()->create(request()->validate([
               'id' => 'required',
               'user_id' => 'required',
               'country' => 'required',
            
        ]));
        
        //redirect
        return redirect('/orders');
    }
    
    public function show(Order $order) 
    {
        if(auth()->id() != $order->user_id) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }
}
