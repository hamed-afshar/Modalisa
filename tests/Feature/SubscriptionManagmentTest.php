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

class SubscriptionManagmentTest extends TestCase
{

    use WithFaker,
        RefreshDatabase;

    public function prepare_SystemAdmin_env($role, $request, $confirmed, $locked)
    {
        $user = factory('App\User')->create(['id' => '1', 'confirmed' => $confirmed, 'locked' => $locked]);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->role()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->rolePermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

    /** @test */
    public function only_SystemAdmin_can_see_subscriptions()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-subscriptions', 1, 0);
        $subscription = factory('App\Subscription')->create();
        $this->get('/subscriptions')->assertSeeText($subscription->id);
    }

    /** @test */
    public function form_is_available_to_create_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-subscriptions', 1, 0);
        $this->get('/subscriptions/create')->assertOk();
    }

    /** @test */
    public function only_SystemAdmin_can_create_subscription()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-subscriptions', 1, 0);
        $attributes = factory('App\Subscription')->raw();
        $this->post('/subscriptions', $attributes)->assertRedirect('/subscriptions');
        $this->assertDatabaseHas('subscriptions', $attributes);
    }

    /** @test */
    public function name_is_required()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-subscriptions', 1, 0);
        $attributes = factory('App\Subscription')->raw(['plan' => '']);
        $this->post('/subscriptions', $attributes)->assertSessionHasErrors('plan');
    }

    /** @test */
    public function cost_percentage_is_required()
    {
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
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        factory('App\Subscription')->create();
        $subscription = Subscription::find(1);
        $this->get($subscription->path() . '/edit')->assertSee($subscription->plan);
    }

    /** @test */
    public function SystemAdmin_can_update_subscription_plan()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $subscription = factory('App\Subscription')->create();
        $this->patch($subscription->path(), [
            'plan' => 'Gold',
            'cost_percentage' => 20,
        ])->assertRedirect($subscription->path());
        $this->assertEquals('Gold', Subscription::where('id', $subscription->id)->value('plan'));
        $this->assertEquals(20, Subscription::where('id', $subscription->id)->value('cost_percentage'));
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_subscription()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'delete-subscriptions', 1, 0);
        factory('App\Subscription')->create();
        $subscription = Subscription::find(1);
        $this->delete($subscription->path())->assertRedirect('subscriptions');
        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }

    /** @test */
    public function UserSubscription_belongs_to_subscription()
    {
        $userSubscription = factory('App\UserSubscription')->create();
        $this->assertInstanceOf('App\Subscription', $userSubscription->owner);
    }

    /** @test */
    public function SystemAdmin_can_see_all_permissions_assigned_to_users()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $userSubscription = factory('App\UserSubscription')->create();
        $this->get('/user-subscriptions')->assertSeeTextInOrder(array($userSubscription->user_id, $userSubscription->subscription_id));
    }

    /** @test */
    public function form_is_available_to_assign_a_subscription_to_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $this->get('/user-subscriptions/create')->assertOk();
    }

    /** @test */
    public function SystemAdmin_can_assign_a_subscription_plan_to_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $subscription = factory('App\Subscription')->create();
        $user = factory('App\User')->create();
        $attributes = [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
        ];
        $this->post('/user-subscriptions', $attributes)->assertRedirect('/user-subscriptions');
        $this->assertDatabaseHas('user_subscriptions', $attributes);
    }

    /** @test */
    public function user_id_is_required_in_subscription_assignment()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $attributes = factory('App\UserSubscription')->raw(['user_id' => '']);
        $this->post('/user-subscriptions', $attributes)->assertSessionHasErrors('user_id');
    }

    /** @test */
    public function subscriptionID_is_required_in_subscription_assignment()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $attributes = factory('App\UserSubscription')->raw(['subscription_id' => '']);
        $this->post('/user-subscriptions', $attributes)->assertSessionHasErrors('subscription_id');
    }

    /** @test */
    public function only_SystemAdmin_can_view_a_single_subscription_assigned_to_user()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $userSubscription = factory('App\UserSubscription')->create();
        $this->get($userSubscription->path())->assertSeeText($userSubscription->id);

    }

    /** @test */
    public function edit_form_for_assign_subscription_is_available()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $userSubscription = factory('App\UserSubscription')->create();
        $this->get($userSubscription->path() . '/edit')->assertSeeTextInOrder(array($userSubscription->user_id, $userSubscription->subscription_id));
    }

    /** @test */
    public function SystemAdmin_can_update_a_subscription()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $newUser = factory('App\User')->create(['id' => 10]);
        $newSubscription = factory('App\Subscription')->create(['id' => 20]);
        $userSubscription = factory('App\UserSubscription')->create();
        $this->patch($userSubscription->path(), [
            'user_id' => $newUser->id,
            'subscription_id' => $newSubscription->id
        ])->assertRedirect($userSubscription->path());
        $this->assertEquals(10, UserSubscription::where('id', $userSubscription->id)->value('user_id'));
        $this->assertEquals(20, UserSubscription::where('id', $userSubscription->id)->value('subscription_id'));
    }

    /** @test */
    public function SystemAdmin_can_delete_a_user_subscription()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-subscriptions', 1, 0);
        $userSubscription = factory('App\UserSubscription')->create();
        $this->delete($userSubscription->path())->assertRedirect('/user-subscriptions');
        $this->assertDatabaseMissing('user_subscriptions', ['id' => $userSubscription->id]);
    }


    /** @test */
    public function other_users_can_not_access_subscription_system()
    {
        $this->prepare_SystemAdmin_env('retailer', 'submit-order', 1, 0);
        $subscription = factory('App\Subscription')->create();
        $userSubscription = factory('App\UserSubscription')->create();
        //other users can not see subscriptions list
        $this->get('/subscriptions')->assertRedirect('/access-denied');
        //other users can not see subscription creation form
        $this->get('/subscriptions/create')->assertRedirect('/access-denied');
        //other users can not make a subscription
        $this->post('/subscriptions')->assertRedirect('/access-denied');
        //other users can not see a single subscription
        $this->get($subscription->path())->assertRedirect('/access-denied');
        //other users can not see edit subscription form
        $this->get($subscription->path() . '/edit')->assertRedirect('/access-denied');
        //other users can not update a subscription
        $this->patch($subscription->path())->assertRedirect('/access-denied');
        //other users can not delete a subscription
        $this->delete($subscription->path())->assertRedirect('/access-denied');

        //other users can not see user-subscriptions list
        $this->get('/user-subscriptions')->assertRedirect('/access-denied');
        //other users can not see user-subscriptions creation form
        $this->get('/user-subscriptions/create')->assertRedirect('/access-denied');
        //other users can not make a user-subscriptions
        $this->post('/user-subscriptions')->assertRedirect('/access-denied');
        //other users can not see a single user subscription
        $this->get($userSubscription->path())->assertRedirect('/access-denied');
        //other users can not see edit user-subscriptions form
        $this->get($userSubscription->path() . '/edit')->assertRedirect('/access-denied');
        //other users can not update a user-subscriptions
        $this->patch($userSubscription->path())->assertRedirect('/access-denied');
        //other users can not delete a user-subscriptions
        $this->delete($userSubscription->path())->assertRedirect('/access-denied');

    }

    /** @test */
    public function guests_can_not_access_subscriptions_system()
    {
        $subscription = factory('App\Subscription')->create();
        $userSubscription = factory('App\UserSubscription')->create();
        //guests can not see subscription list
        $this->get('/subscriptions')->assertRedirect('login');
        //guests can not see subscription creation form
        $this->get('/subscriptions/create')->assertRedirect('login');
        //guests can not make subscription
        $this->post('/subscriptions')->assertRedirect('login');
        //guests can not see a single subscription
        $this->get($subscription->path())->assertRedirect('login');
        //guests can not see subscription edit form
        $this->get($subscription->path() . '/edit')->assertRedirect('login');
        //guests can not update a subscription
        $this->patch($subscription->path())->assertRedirect('login');
        //guests can not delete a subscription
        $this->delete($subscription->path())->assertRedirect('login');

        //guests can not see user-subscription list
        $this->get('/user-subscriptions')->assertRedirect('login');
        //guests can not see user-subscription creation form
        $this->get('/user-subscriptions/create')->assertRedirect('login');
        //guests can not make user-subscription
        $this->post('/user-subscriptions')->assertRedirect('login');
        //guests can not see a single user-subscription
        $this->get($userSubscription->path())->assertRedirect('login');
        //guests can not see user-subscription edit form
        $this->get($userSubscription->path() . '/edit')->assertRedirect('login');
        //guests can not update a user-subscription
        $this->patch($userSubscription->path())->assertRedirect('login');
        //guests can not delete a user-subscription
        $this->delete($userSubscription->path())->assertRedirect('login');
    }
}
