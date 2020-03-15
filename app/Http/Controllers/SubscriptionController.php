<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\User;
use App\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{

    // index subscriptions
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('subscriptions.index', compact('subscriptions'));
    }


    //show subscriptions create form
    public function create()
    {
        return view('subscriptions.create');
    }

    // store subscriptions
    public function store()
    {
        Subscription::create(request()->validate([
            'plan' => 'required',
            'cost_percentage' => 'required'
        ]));
    }


    // show a single subscription
    public function show(Subscription $subscription)
    {
        return view('subscriptions.show', compact('subscription'));
    }

    //edit form is available
    public function edit(Subscription $subscription)
    {
        return view('subscriptions.edit', compact('subscription'));
    }

    // update a subscription
    public function update(Subscription $subscription)
    {
        $data = request()->validate([
            'plan' => 'required',
            'cost_percentage' => 'required'
        ]);
        $subscription->update($data);
    }

    //delete a subscription
    public
    function destroy(Subscription $subscription)
    {
        $subscription->delete();
    }

    /** assign a subscription to a user
     *
     */
    public
    function assignSubscription()
    {
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
    public
    function indexUserSubscription()
    {
        if (auth()->user()->getAccessLevel() != 'SystemAdmin') {
            return auth()->user()->showAccessDenied();
        } else {
            $userSubscription = UserSubscription::all();
            return view('subscriptions.user-subscription', compact('userSubscription'));
        }
    }

}
