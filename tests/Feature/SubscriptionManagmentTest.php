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

    /** @test */
    public function only_SystemAdmin_can_see_subscriptions()
    {
        $this->prepAdminEnv('SystemAdmin', 0 , 1);
        //$subscription = factory('App\Subscription')->create();
        //dd($subscription);
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
