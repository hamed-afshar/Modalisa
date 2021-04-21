<?php

namespace App\Http\Controllers\API;

use App\Exceptions\PermissionExist;
use App\Exceptions\SubscriptionExist;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Subscription;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        $subscriptions = Subscription::all();
        return response(['subscriptions' => SubscriptionResource::collection($subscriptions), 'message' => trans('translate.retrieved')], 200 );
    }


    /**
     * only SystemAdmin can create subscriptions
     * store subscriptions
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws SubscriptionExist
     */
    public function store(Request $request)
    {
        $this->authorize('create', Subscription::class);
        $request->validate([
            'plan' => 'required',
            'cost_percentage' => 'required | numeric',
            'kargo_limit' => 'required | numeric'
        ]);
        $subscriptionData = [
            'plan' => $request->input('plan'),
            'cost_percentage' => $request->input('cost_percentage'),
            'kargo_limit' => $request->input('kargo_limit')
        ];
        //check to see if the subscription name is already exist in db
        $check = DB::table('subscriptions')->where('plan','=',$subscriptionData['plan'])->first();
        if($check != null) {
            throw new SubscriptionExist();
        }
        $subscription = Subscription::create($subscriptionData);
        return response(['subscriptions' => new SubscriptionResource($subscription), 'message' => trans('translate.retrieved')], 200 );
    }


    /**
     * vue-js modal generates this form
     * show a single subscription
     * @param Subscription $subscription
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);
        return response(['subscriptions' => new SubscriptionResource($subscription), 'message' => trans('translate.retrieved')], 200 );
    }

    /**
     * update a subscription
     * only SystemAdmin can update subscriptions
     * @param Request $request
     * @param Subscription $subscription
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws SubscriptionExist
     */
    public function update(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        $request->validate([
            'plan' => 'required',
            'cost_percentage' => 'required|numeric',
            'kargo_limit' => 'required|numeric'
        ]);
        $subscriptionData = [
            'plan' => $request->input('plan'),
            'cost_percentage' => $request->input('cost_percentage'),
            'kargo_limit' => $request->input('kargo_limit')
        ];
        //check to see if the subscription name is already exist in db
        $check = DB::table('subscriptions')->where('plan','=',$subscriptionData['plan'])->first();
        if($check != null) {
            throw new SubscriptionExist();
        }
        $subscription->update($subscriptionData);
        return response(['subscriptions' => new SubscriptionResource($subscription), 'message' => trans('translate.retrieved')], 200 );
    }

    /**
     * only SystemAdmin can delete subscriptions
     * delete a subscription
     * @param Subscription $subscription
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);
        $subscription->delete();
        return response(['message' => trans('translate.deleted')], 200 );
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
        $this->authorize('update', $subscription);
        $subscription->changeSubscription($user);
        return response(['subscriptions' => new SubscriptionResource($subscription), 'message' => trans('translate.retrieved')], 200 );

    }
}
