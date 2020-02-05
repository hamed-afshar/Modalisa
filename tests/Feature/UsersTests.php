<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UsersTests extends TestCase {

    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function retailers_can_register_into_the_system() {
        $this->withoutExceptionHandling();
        $attributes = factory('App\User')->raw();
        $this->post('/register-retailers', $attributes)->assertOk();
        $this->assertCount(1, User::all());
    }
    
    /** @test */
    public function a_name_is_requiered() {
//        $this->withoutExceptionHandling();
        $attributes = factory('App\User')->raw(['name' => '']);
        $this->post('/register-retailers', $attributes)->assertSessionHasErrors('name');
    }

}
