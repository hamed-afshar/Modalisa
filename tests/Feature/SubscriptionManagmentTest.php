<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use App\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\UserSubscription;

class SubscriptionManagmentTest extends TestCase {

    use WithFaker,
        RefreshDatabase;

    public function prepare_SystemAdmin_env($role, $request, $confirmed, $locked)
    {
        $user = factory('App\User')->create(['id' => '1', 'confirmed' => $confirmed, 'locked' => $locked]);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->roles()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->assignedPermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

    /** @test */
    public function only_SystemAdmin_can_see_subscriptions() {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-subscriptions', 1,0);
        $subscription  = factory('App\Subscription')->create();
        $this->get('/subscriptions')->assertSee($subscription->id);
    }

    /** @test */
    public function form_is_available_to_create_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-subscriptions', 1,0);
        $this->get('/subscriptions/create')->assertOk();
    }

    /** @test */
    public function only_SystemAdmin_can_create_subscription()
    {

        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-subscriptions', 1, 0);
        $attributes = factory('App\Subscription')->raw();
        $this->post('/subscriptions', $attributes);
        $this->assertDatabaseHas('subscriptions', $attributes);
    }

    /** @test */
    public function name_is_required() {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-subscriptions', 1, 0);
        $attributes = factory('App\Subscription')->raw(['plan' => '']);
        $this->post('/subscriptions', $attributes)->assertSessionHasErrors('plan');
    }

    /** @test */
    public function cost_percentage_is_required() {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-subscriptions', 1, 0);
        $attributes = factory('App\Subscription')->raw(['cost_percentage' => '']);
        $this->post('/subscriptions', $attributes)->assertSessionHasErrors('cost_percentage');
    }

    /** @test */
    public function only_SystemAdmin_can_vew_a_single_subscription()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-subscriptions', 1, 0);
        factory('App\Subscription')->create();
        $subscription = Subscription::find(1);
        $this->get($subscription->path())->assertSeeText($subscription->plan);
    }

    /** @test */
    public function form_is_available_to_edit_a_subscription()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        factory('App\Subscription')->create();
        $subscription = Subscription::find(1);
        $this->get($subscription->path() . '/edit')->assertSee($subscription->plan);
//        $this->get('/subscriptions/1/edit')->assertSee($subscription->plan);
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
        //other users can not see subscriptions list
        $this->get('/subscriptions')->assertRedirect('/access-denied');
        //other users can not make a subscription
        $this->post('/subscriptions')->assertRedirect('/access-denied');
        //other users can not edit subscription
        $this->patch('/subscriptions/' . $subscription->id)->assertRedirect('/access-denied');
        //other users can not assign subscription to users
        $this->post('/user-subscription')->assertRedirect('/access-denied');
        //other users can not view users subscription
        $this->get('/user-subscription')->assertRedirect('/access-denied');
    }

    /** @test */
    public function guests_can_not_access_subscriptions_system() {
//        $this->withoutExceptionHandling();
        $subscription = factory('App\Subscription')->create();
        //guests can not see subscription list
        $this->get('/subscriptions')->assertRedirect('login');
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
