<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\UserSubscription;

class SubscriptionManagmentTest extends TestCase {

    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function SystemAdmin_can_define_subscription_plan() {
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $attributes = factory('App\Subscription')->raw();
        $this->post('/subscriptions', $attributes)->assertRedirect('/subscriptions');
        $this->assertDatabaseHas('Subscriptions', $attributes);
    }

    /** @test */
    public function subscription_requires_a_plan() {
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $attributes = factory('App\Subscription')->raw(['plan' => '']);
        $this->post('/subscriptions', $attributes)->assertSessionHasErrors('plan');
    }

    /** @test */
    public function subscription_requires_a_cost_percentage() {
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $attributes = factory('App\Subscription')->raw(['cost_percentage' => '']);
        $this->post('/subscriptions', $attributes)->assertSessionHasErrors('cost_percentage');
    }

    /** @test */
    public function SystemAdmin_can_edit_subscription_plan() {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $subscription = factory('App\Subscription')->create();
        $this->patch('/subscriptions/' . $subscription->id, [
            'plan' => 'Gold',
            'cost_percentage' => 20,
        ]);
        $this->assertEquals('Gold', DB::table('subscriptions')->where('id', $subscription->id)->value('plan'));
        $this->assertEquals(20, DB::table('subscriptions')->where('id', $subscription->id)->value('cost_percentage'));
    }

    /** @test */
    public function SystemAdmin_can_assign_a_subscription_plan_to_user() {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $user = factory('App\User')->create(['access_level' => 'Retailer']);
        $subscription = factory('App\Subscription')->create();
        $attributes = [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
        ];
        $this->post('/user-subscription', $attributes)->assertRedirect('/user-subscription');
        $this->assertCount(1, UserSubscription::all());
    }

    /** @test */
    public function userID_is_required_in_subscription_assignment() {
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $attributes = factory('App\UserSubscription')->raw(['user_id' => '']);
        $this->post('/user-subscription', $attributes)->assertSessionHasErrors('user_id');
    }

    /** @test */
    public function subscriptionID_is_required_in_subscription_assignment() {
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $attributes = factory('App\UserSubscription')->raw(['subscription_id' => '']);
        $this->post('/user-subscription', $attributes)->assertSessionHasErrors('subscription_id');
    }

    /** @test */
    public function SystemAdmin_can_view_users_subscription() {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $userSubscription = factory('App\UserSubscription')->create();
        $this->get('/user-subscription')->assertSee($userSubscription->user_id);
    }

    /** @test */
    public function other_users_can_not_access_subscription_system() {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create(['access_level' => 'Retailer']);
        $subscription = factory('App\Subscription')->create();
        $this->actingAs($user);
        //other users can not make a subscription
        $this->post('/subscriptions')->assertRedirect('/access-denied');
        //other users can not edit subscription
        $this->patch('/subscriptions/' . $subscription->id)->assertRedirect('/access-denied');
    }

    /** @test */
    public function guests_can_not_access_subscriptions_system() {
//        $this->withoutExceptionHandling();
        $subscription = factory('App\Subscription')->create();
        //guests can not make susbciption
        $this->post('/subscriptions')->assertRedirect('login');
        //guests can not edit subscription
        $this->patch('/subscriptions/' . $subscription->id)->assertRedirect('login');
        //guests can not assign subscription to users
        $this->post('/user-subscription')->assertRedirect('login');
        //gustes can not view users subscriptions
        $this->get('/user-subscription')->assertRedirect('login');
    }

}
