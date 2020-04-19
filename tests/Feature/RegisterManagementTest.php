<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function form_is_available_to_create_user()
    {
        $this->get('/users/create')->assertOk();
    }

    /** @test */
    public function users_can_register_in_system()
    {
        $attributes = factory('App\User')->raw();
        $this->post('/users', $attributes)->assertSee('confirmation');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $attributes = factory('App\User')->raw(['name' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_email_is_required()
    {
        $attributes = factory('App\User')->raw(['email' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required()
    {
        $attributes = factory('App\User')->raw(['password' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('password');
    }

    /** @test */
    public function language_is_required()
    {
        $attributes = factory('App\User')->raw(['language' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('language');
    }

    /** @test */
    public function tel_is_required()
    {
        $attributes = factory('App\User')->raw(['tel' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('tel');
    }

    /** @test */
    public function country_is_required()
    {
        $attributes = factory('App\User')->raw(['country' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('country');
    }

    /** @test */
    public function communication_media_is_required()
    {
        $attributes = factory('App\User')->raw(['communication_media' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('communication_media');
    }
}
