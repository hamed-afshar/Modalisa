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
        $this->post('/register', $attributes)->assertOk();
        $this->assertCount(1, User::all());
    }
    
    /** @test */
    public function a_name_is_required() {
//        $this->withoutExceptionHandling();
        $attributes = factory('App\User')->raw(['name' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('name');
    }
    
    /** @test */
    public function an_email_is_required () {
        $attributes = factory('App\User')->raw(['email' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('email');
    }
     
    /** @test */
    public function password_is_required() {
        $attributes = factory('App\User')->raw(['password' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('password');
    }
    
    /** @test */
    public function language_is_required() {
        $attributes = factory('App\User')->raw(['language' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('language');
    }
    
    /** @test */
    public function tel_is_required() {
        $attributes = factory('App\User')->raw(['tel' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('tel');
    }
    
    /** @test */
    public function country_is_required() {
        $attributes = factory('App\User')->raw(['country' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('country');
    }
    
    /** @test */
    public function communication_media_is_required() {
        $attributes = factory('App\User')->raw(['communication_media' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('communication_media');
    }
    
    /** @test */
    public function only_SystemAdmin_users_can_see_the_all_users_list() {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create(["access_level" => "SystemAdmin"]));
        $this->get('/all-users')->assertSee(User::g);
    }
}
