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
        dd("subs index policy");
        $this->authorize('viewAny', Subscription::class);
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
        return redirect()->route('subscriptions.index');
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
            'cost_percentage' => 'required|integer'
        ]);
        $subscription->update($data);
        return redirect()->route('subscriptions.show', $subscription);
    }

    //delete a subscription
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index');
    }
}
