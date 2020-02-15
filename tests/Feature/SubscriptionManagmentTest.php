<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionManagmentTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    /** @test */
    public function SystemAdmin_can_define_subscription_plan() 
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $attributes = factory('App\Subscription')->raw();
        $this->post('/subscriptions', $attributes)->assertRedirect('/subscriptions');
        $this->assertDatabaseHas('Subscriptions', $attributes);
    }
    
    /** @test */
    public function other_users_can_not_access_admin_sections()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create(['access_level' => 'Retailer']);
        $this->actingAs($user);
        //other users can not make a subscription
        $this->post('/subscriptions')->assertRedirect('/access-denied');
    }
}
