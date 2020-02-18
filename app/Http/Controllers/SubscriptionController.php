<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\User;
use App\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store() {
        //validate and persist
        if (auth()->user()->getAccessLevel() != 'SystemAdmin') {
            return auth()->user()->showAccessDenied();
        } else {
            $data = request()->validate([
                'plan' => 'required',
                'cost_percentage' => 'required'
            ]);
            Subscription::create([
                'id' => request('id'),
                'plan' => request('plan'),
                'cost_percentage' => request('cost_percentage')
            ]);
            return redirect('subscriptions');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Subscription $subscription) {
        if (auth()->user()->getAccessLevel() != "SystemAdmin") {
            return auth()->user()->showAccessDenied();
        } else {
            $data = request()->validate([
                'plan' => 'required',
                'cost_percentage' => 'required'
            ]);
            $subscription->update($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription) {
        //
    }

    /** assign a subscription to a user
     * 
     */
    public function assignSubscription() {
        if (auth()->user()->getAccessLevel() != 'SystemAdmin') {
            return auth()->user()->showAccessDenied();
        } else {
            $data = request()->validate([
                'user_id' => 'required',
                'subscription_id' => 'required'
            ]);
            UserSubscription::create([
                'user_id' => request('user_id'),
                'subscription_id' => request('subscription_id')
            ]);
            return redirect('/user-subscription');
        }
    }
    
    /** index all assigned subscriptions
     * 
     */
    public function indexUserSubscription() {
        if(auth()->user()->getAccessLevel() != 'SystemAdmin') {
            return auth()->user()->showAccessDenied();
        } else {
            $userSubscription = UserSubscription::all();
            return view('subscriptions.user-subscription', compact($userSubscription));
        }
    }

}
