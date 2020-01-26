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
        $this->post('/orders', $attributes);
        $this->assertDatabaseHas('Orders', $attributes);
                
    }
}
