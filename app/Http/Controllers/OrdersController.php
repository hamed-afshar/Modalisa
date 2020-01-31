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
               'orderID' => 'required',
            //'users_id' => 'required',
            'Status_statusID' => 'required',
            //'created_at' => 'required',
            'country' => 'required',
            //'updated_at' => 'required'
        ]));
        
        //redirect
        return redirect('/orders');
    }
    
    public function show(Order $order) 
    {
        if(auth()->id() != $order->users_id) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }
}
