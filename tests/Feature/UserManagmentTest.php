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
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $this->get('/users')->assertSee(auth()->user()->name);
    }

    /** @test */
    public function only_SystemAdmin_can_vew_a_single_user()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
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
        $this->prepAdminEnv('retailer', 1, 0);
        $user = User::find(1);
        $this->get($user->path() . '/edit')->assertSee($user->name);
    }

    /** @test */
    //members can update their profiles
    public function users_can_update_their_profiles()
    {
        $this->prepNormalEnv('retailer', 'edit-profile', 0, 1);
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
        $this->prepAdminEnv('SystemAdmin', 0,1);
        $user = User::find(1);
        $this->delete($user->path())->assertSeeText("unauthorized");

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
