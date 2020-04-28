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
        $this->authorize('viewAny', Subscription::class);
        $subscriptions = Subscription::all();
        return view('subscriptions.index', compact('subscriptions'));
    }


    //show subscriptions create form
    public function create()
    {
        $this->authorize('create', Subscription::class);
        return view('subscriptions.create');
    }

    // store subscriptions
    public function store()
    {
        $this->authorize('create', Subscription::class);
        Subscription::create(request()->validate([
            'plan' => 'required',
            'cost_percentage' => 'required'
        ]));
        return redirect()->route('subscriptions.index');
    }


    // show a single subscription
    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);
        return view('subscriptions.show', compact('subscription'));
    }

    //edit form is available
    public function edit(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        return view('subscriptions.edit', compact('subscription'));
    }

    // update a subscription
    public function update(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
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
        $this->authorize('delete', $subscription);
        $subscription->delete();
        return redirect()->route('subscriptions.index');
    }
}
