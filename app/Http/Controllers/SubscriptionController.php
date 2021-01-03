<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * index subscriptions
     * only SystemAdmin can index subscriptions
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Subscription::class);
        return $subscriptions = Subscription::all();
    }

    /**
     * vue-js modal generates this form
     * show subscriptions create form
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Subscription::class);
    }

    /**
     * only SystemAdmin can create subscriptions
     * store subscriptions
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Subscription::class);
        $request->validate([
            'plan' => 'required',
            'cost_percentage' => 'required | numeric'
        ]);
        $subscriptionData = [
            'plan' => $request->input('plan'),
            'cost_percentage' => $request->input('cost_percentage')
        ];
        Subscription::create($subscriptionData);
        return redirect()->route('subscriptions.index');
    }


    /**
     * vue-js modal generates this form
     * show a single subscription
     * @param Subscription $subscription
     * @throws AuthorizationException
     */
    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);
    }

    /**
     * vue-js modal generates this form
     * edit form is available
     * @param Subscription $subscription
     * @throws AuthorizationException
     */
    public function edit(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
    }

    /**
     * update a subscription
     * only SystemAdmin can update subscriptions
     * @param Subscription $subscription
     * @throws AuthorizationException
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

    /**
     * only SystemAdmin can delete subscriptions
     * delete a subscription
     * @param Subscription $subscription
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);
        $subscription->delete();
    }

    /**
     * only SystemAdmin can change user's subscriptions
     * change user's subscription
     * @param Subscription $subscription
     * @param User $user
     * @throws AuthorizationException
     */
    public function changeSubscription(Subscription $subscription, User $user)
    {
        $this->authorize('update', $subscription );
        $subscription->changeSubscription($user);
    }
}
