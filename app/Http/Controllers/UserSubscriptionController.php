<?php

namespace App\Http\Controllers;

use App\UserSubscription;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller
{
    //index
    public function index()
    {
        $userSubscriptions = UserSubscription::all();
        return view('user-subscriptions.index', compact('userSubscriptions'));
    }

    //assign subscription form
    public function create()
    {
        return view('user-subscriptions.create');
    }

    //store
    public function store()
    {
        UserSubscription::create(request()->validate([
            'user_id' => 'required',
            'subscription_id' => 'required'
        ]));
        return redirect()->route('user-subscriptions.index');
    }

    //show a single user subscription
    public function show(UserSubscription $userSubscription)
    {
        return view('user-subscriptions.show', compact('userSubscription'));
    }

    //edit form for update
    public function edit(UserSubscription $userSubscription)
    {
        return view('user-subscriptions.edit', compact('userSubscription'));
    }

    //update a subscription
    public function update(UserSubscription $userSubscription)
    {
        $data = request()->validate([
            'user_id' => 'required',
            'subscription_id' => 'required'
        ]);
        $userSubscription->update($data);
        return redirect()->route('user-subscriptions.show', $userSubscription);
    }

    //delete a subscription
    public function destroy(UserSubscription $userSubscription)
    {
        $userSubscription->delete();
        return redirect()->route('user-subscriptions.index');
    }
}
