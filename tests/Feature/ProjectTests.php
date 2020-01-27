<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTests extends TestCase
{
    use WithFaker, RefreshDatabase;
    /** @test */
    public function a_user_can_make_a_order() 
    {
        $this->withoutExceptionHandling();
        $attributes = [
            'orderID' => $this->faker->numberBetween($min = 3000, $max = 4000),
            'Users_id' => 3,
            'Status_statusID' => 1,
            'created_at' => $this->faker->dateTime,
            'country' => 'Turkey'
        ];
        $this->post('/orders', $attributes)->assertRedirect('/orders');
        $this->assertDatabaseHas('Orders', $attributes);
        $this->get('/orders')->assertSee($attributes['orderID']);
                
    }
    /** @test */
    public function an_order_requires_all_fields() {
        $attributes = factory('App\order')->make();
        $this->post('\orders', [])->assertSessionHasErrors('orderID');
    
        
    }
}
