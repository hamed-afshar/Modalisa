<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\User;
use App\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /*
     * index subscriptions
     */
    public function index()
    {
        $this->authorize('viewAny', Subscription::class);
        return $subscriptions = Subscription::all();
    }

    /*
     * vue-js modal generates this form
     * show subscriptions create form
     */
    public function create()
    {
        $this->authorize('create', Subscription::class);
    }

    /*
     * store subscriptions
     */
    public function store()
    {
        $this->authorize('create', Subscription::class);
        Subscription::create(request()->validate([
            'plan' => 'required',
            'cost_percentage' => 'required | numeric'
        ]));
        return redirect()->route('subscriptions.index');
    }


    /*
     * vue-js modal generates this form
     * show a single subscription
     */
    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);
    }

    /*
     * vue-js modal generates this form
     * edit form is available
     */
    public function edit(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
    }

    /*
     * update a subscription
     */
    public function update(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        $data = request()->validate([
            'plan' => 'required',
            'cost_percentage' => 'required|integer'
        ]);
        $subscription->update($data);
    }

    /*
     * delete a subscription
     */
    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);
        $subscription->delete();
    }
}
