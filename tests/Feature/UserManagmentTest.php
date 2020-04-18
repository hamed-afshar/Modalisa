<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;

class UserManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_see_users()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create(['name' => 'SystemAdmin']);
        $user->assignRole($role);
        Auth::login($user);
        $this->get('/users')->assertSee($user->name);
    }

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

    /** @test */
    public function only_SystemAdmin_can_vew_a_single_user()
    {
        $user = User::find(1);
        $this->get($user->path())->assertSee($user->name);
        $this->assertInstanceOf(Carbon::class, $user->last_login);
        $this->assertInstanceOf(Carbon::class, $user->created_at);
        $this->assertInstanceOf(Carbon::class, $user->updated_at);
    }

    /** @test */
    //members have form to update their profiles
    public function form_is_available_to_edit_a_user()
    {
        $this->prepare_other_users_env('retailer', 'edit-profile', 1, 0);
        $user = User::find(1);
        $this->get($user->path() . '/edit')->assertSee($user->name);
    }

    /** @test */
    //members can update their profiles
    public function users_can_update_their_profiles()
    {
        $this->prepare_other_users_env('retailer', 'edit-profile', 1, 0);
        $newDetails = factory('App\User')->raw();
        $user = User::find(1);
        $this->patch($user->path(), [
            'email' => $newDetails['email'],
            'password' => $newDetails['password'],
            'language' => $newDetails['language'],
            'tel' => $newDetails['tel'],
            'country' => $newDetails['country'],
            'communication_media' => $newDetails['communication_media']
        ]);
        $this->assertEquals($newDetails['email'], User::where('id', $user->id)->value('email'));
    }

    /** @test */
    public function users_can_not_be_deleted_from_system()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'delete-user', 1, 0);
        $user = User::find(1);
        $this->delete($user->path())->assertRedirect('/access-denied');

    }

    /** @test */
    public function locked_users_can_not_access_system()
    {
        $this->prepare_other_users_env('retailer', 'edit-profile', 1, 1);
        $user = User::find(1)->first();
        //for example accessing edit form
        $this->get($user->path() . '/edit')->assertRedirect('/locked');
    }

    /** @test */
    public function not_confirmed_users_can_not_access_system()
    {
        $this->prepare_other_users_env('retailer', 'submit-orders', 0, 0);
        $user = User::find(1)->first();
        //for example accessing edit form
        $this->get($user->path())->assertRedirect('/pending-for-confirmation');
    }

    /** @test */
    public function other_users_can_not_access_user_management()
    {
        $this->prepare_other_users_env('retailer', 'submit-orders', 1, 0);
        $this->get('/users')->assertRedirect('/access-denied');
        $user = User::find(1);
        $this->get($user->path())->assertRedirect('/access-denied');
        $this->delete($user->path())->assertRedirect('/access-denied');
    }

    /** @test */
    public function guest_can_not_access_user_management_system()
    {
        $this->get('/users')->assertRedirect('login');
        $this->get('/users/1')->assertRedirect('login');
        $this->get('users/1/edit')->assertRedirect('login');
        $this->patch('users/1')->assertRedirect('login');
        $this->delete('/users/1')->assertRedirect('login');

    }

}
