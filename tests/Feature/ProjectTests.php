<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTests extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    /** @test */
    public function guests_cannot_create_orders()
    {
        //$this->withoutExceptionHandling();
        $attributes = factory('App\Order')->raw();
        $this->post('/orders', $attributes)->assertRedirect('login');
    }
    
    /** @test */
    public function guests_cannot_view_orders()
    {
        $this->get('/orders')->assertRedirect('login');
    }
    
    /** @test */
    public function guests_cannot_view_a_single_order()
    {
        $order = factory('App\Order')->create();
        $this->get($order->path())->assertRedirect('login');
    }
    
    /** @test */
    
    public function a_user_can_make_an_order() 
    {
       $this->withoutExceptionHandling();
       $user = factory('App\User')->create();
       $this->actingAs($user);
       $attributes = [
            'orderID' => $this->faker->numberBetween($min = 3000, $max = 4000),
            'users_id' => $user->id,
            'country' => 'Turkey'
        ];
        $this->post('/orders', $attributes)->assertRedirect('/orders');
        $this->assertDatabaseHas('Orders', $attributes);
        $this->get('/orders')->assertSee($attributes['orderID']);
                
    }
    /** @test */
    public function an_order_requires_orderID() 
    {   
        //$this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Order')->raw(['orderID' => '']);
        $this->post('/orders', $attributes)->assertSessionHasErrors('orderID');
       
    }
    
    /** @test */
    public function a_user_can_view_their_order() 
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $order =  factory('App\Order')->create(['users_id' => auth()->id()]);
        $this->get($order->path())
              ->assertSee($order->orderID);
    }
    
    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others() 
    {
        $this->be(factory('App\User')->create());
        //$this->withoutExceptionHandling();
        $order = factory('App\Order')->create();
        $this->get($order->path())->assertStatus(403);
    }
    
    /** @test */
    public function it_belongs_to_a_user() 
    {
        $order = factory('App\Order')->create();
        $this->assertInstanceOf('App\User', $order->owner);
    }
    
    
}
