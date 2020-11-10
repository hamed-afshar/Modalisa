<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use App\Subscription;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\UserSubscription;

class SubscriptionManagmentTest extends TestCase
{

    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function other_users_can_not_make_changes_to_the_subscriptions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $subscription = factory('App\Subscription')->create();
        $newAttributes = factory('App\Subscription')->raw();
        $this->get($subscription->path())->assertForbidden();
        $this->get($subscription->path() . '/create')->status(404);
        $this->post('/subscriptions', $newAttributes)->assertForbidden();
        $this->get($subscription->path())->assertForbidden();
        $this->get($subscription->path() . '/edit')->status(404);
        $this->patch($subscription->path(), $newAttributes)->assertForbidden();
        $this->delete($subscription->path())->assertForbidden();

    }

    /** @test */
    public function SystemAdmin_can_change_user_subscription()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $newUser = factory('App\User')->create();
        $newSubscription = factory('App\Subscription')->create(['plan' => 'Gold']);
        $this->get('/change-subscriptions/' . $newSubscription->id . '/' . $newUser->id);
        $this->assertDatabaseHas('users', ['subscription_id' => $newSubscription->id]);

    }

    /** @test */
    public function only_SystemAdmin_can_see_subscriptions()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $subscription = factory('App\Subscription')->create();
        $this->get('/subscriptions')->assertSeeText($subscription->plan);
    }

    /*
      * this should be tested in VueJs
      */
    public function form_is_available_to_create_roles()
    {
    }

    /** @test */
    public function only_SystemAdmin_can_create_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Subscription')->raw();
        $this->post('/subscriptions', $attributes);
        $this->assertDatabaseHas('subscriptions', $attributes);
    }

    /** @test */
    public function name_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Subscription')->raw(['plan' => '']);
        $this->post('/subscriptions', $attributes)->assertSessionHasErrors('plan');
    }

    /** @test */
    public function cost_percentage_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Subscription')->raw(['cost_percentage' => '']);
        $this->post('/subscriptions', $attributes)->assertSessionHasErrors('cost_percentage');
    }

    /*
      * This test is not necessary
      */
    public function only_SystemAdmin_can_vew_a_single_subscription()
    {

    }

    /*
    * this should be tested in VueJs
    */
    public function form_is_available_to_update_a_subscription()
    {

    }

    /** @test */
    public function only_SystemAdmin_can_update_a_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $subscription = factory('App\Subscription')->create();
        $this->patch($subscription->path(), [
            'plan' => 'Gold',
            'cost_percentage' => 20,
        ]);
        $this->assertDatabaseHas('subscriptions', ['id' => $subscription->id]);
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_subscription()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        factory('App\Subscription')->create();
        $subscription = Subscription::find(1);
        $this->delete($subscription->path());
        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }

    /** @test
     * one to many relationship
     */
    public function subscriptions_have_many_users()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $subscription = Subscription::find(1);
        $user = User::find(1);
        $subscription->changeSubscription($user);
        $this->assertInstanceOf(User::class, $subscription->users->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function users_belongs_to_one_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0 , 1);
        $subscription = Subscription::find(1);
        $user = User::find(1);
        $subscription->changeSubscription($user);
        $this->assertInstanceOf(Subscription::class, $user->subscription->find(1));
    }


    /** @test */
    public function guests_can_not_access_subscriptions_system()
    {
        $subscription = factory('App\Subscription')->create();
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
    }
}
