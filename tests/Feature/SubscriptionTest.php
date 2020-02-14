<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    /** @test */
    public function SystemAdmin_can_define_subscription_plan() 
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $attributes = factory('App\Subscription')->raw();
        $response = $this->post('/subscriptions', $attributes);
        $response->assertRedirect('/subscriptions');
        
    }
}
