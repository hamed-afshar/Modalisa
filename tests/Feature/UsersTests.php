<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTests extends TestCase {

    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function retailers_can_register_into_the_system() {
        $this->withExceptionHandling();
        $attributes = factory('App\User')->create();
        $this->post('/users', $attributes);
        $this->assertDatabaseHas('users', $attributes);
    }

}
