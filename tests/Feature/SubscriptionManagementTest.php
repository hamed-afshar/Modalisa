<?php

namespace Tests\Feature;

use App\Subscription;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SubscriptionManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_see_subscriptions()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $subscription = factory('App\Subscription')->create(['plan' => 'test-plan']);
        $this->get('api/subscriptions')->assertSeeText($subscription->plan);
        //other users are not allowed to see subscriptions
        $this->prepNormalEnv('retailer', ['create-orders', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get('api/subscriptions')->assertForbidden();
    }

    /** @test */
    public function only_SystemAdmin_can_create_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $attributes = factory('App\Subscription')->raw();
        $this->post('api/subscriptions', $attributes);
        $this->assertDatabaseHas('subscriptions', $attributes);
        //other users are not allowed to create subscriptions
        $this->prepNormalEnv('retailer', ['create-orders', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->post('api/subscriptions', $attributes)->assertForbidden();
    }

    /** @test */
    public function plan_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $attributes = factory('App\Subscription')->raw(['plan' => '']);
        $this->post('api/subscriptions', $attributes)->assertSessionHasErrors('plan');
    }

    /** @test */
    public function cost_percentage_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $attributes = factory('App\Subscription')->raw(['cost_percentage' => '']);
        $this->post('api/subscriptions', $attributes)->assertSessionHasErrors('cost_percentage');
    }

    /** @test */
    public function kargo_limit_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $attributes = factory('App\Subscription')->raw(['kargo_limit' => '']);
        $this->post('api/subscriptions', $attributes)->assertSessionHasErrors('kargo_limit');
    }


    /** @test */
    public function only_SystemAdmin_can_vew_a_single_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $subscription = factory('App\Subscription')->create();
        $this->get($subscription->path())->assertSeeText($subscription->plan);
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get($subscription->path())->assertForbidden();
    }


    /** @test */
    public function only_SystemAdmin_can_update_a_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $subscription = factory('App\Subscription')->create(['plan' => 'test-plan']);
        $newAttributes = [
            'plan' => 'Platinum',
            'cost_percentage' => 20,
            'kargo_limit' => 10
        ];
        $this->patch($subscription->path(), $newAttributes);
        $this->assertDatabaseHas('subscriptions', [
            'plan' => 'Platinum',
            'cost_percentage' => 20
        ]);
        //other users are not allowed to update subscriptions
        $this->prepNormalEnv('retailer', ['create-orders', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->patch($subscription->path(), $newAttributes)->assertForbidden();
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['create-orders', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        factory('App\Subscription')->create();
        $subscription = Subscription::find(1);
        //other users are not allowed to delete subscriptions
        $this->actingAs($retailer, 'api');
        $this->delete($subscription->path())->assertForbidden();
        //only SystemAdmin can delete subscriptions
        $this->actingAs($SystemAdmin, 'api');
        $this->delete($subscription->path());
        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }

    /** @test */
    public function SystemAdmin_can_change_user_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $newUser = factory('App\User')->create();
        $newSubscription = factory('App\Subscription')->create(['plan' => 'Gold']);
        $this->get('api/change-subscriptions/' . $newSubscription->id . '/' . $newUser->id);
        $this->assertDatabaseHas('users', ['id' => $newUser->id, 'subscription_id' => $newSubscription->id]);
        //other users are not allowed to change subscriptions
        $this->prepNormalEnv('retailer', ['create-orders', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get('api/change-subscriptions/' . $newSubscription->id . '/' . $newUser->id)->assertForbidden();
    }

    /**
     * @test
     * one to many relationship
     */
    public function each_subscription_has_many_users()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $subscription = factory('App\Subscription')->create();
        $user = User::find(1);
        $subscription->changeSubscription($user);
        $this->assertInstanceOf(User::class, $subscription->users->find(1));
    }

    /**
     * @test
     * one to many relationship
     */
    public function each_user_belongs_to_one_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $subscription = factory('App\Subscription')->create();
        $user = User::find(1);
        $subscription->changeSubscription($user);
        $this->assertInstanceOf(Subscription::class, $user->subscription->find(1));
    }

    /** @test */
    public function guests_can_not_access_subscriptions_system()
    {
        $subscription = factory('App\Subscription')->create();
        //guests can not see subscription list
        $this->get('api/subscriptions')->assertRedirect('login');
        //guests can not make subscription
        $this->post('api/subscriptions')->assertRedirect('login');
        //guests can not see a single subscription
        $this->get($subscription->path())->assertRedirect('login');
        //guests can not update a subscription
        $this->patch($subscription->path())->assertRedirect('login');
        //guests can not delete a subscription
        $this->delete($subscription->path())->assertRedirect('login');
    }
}
