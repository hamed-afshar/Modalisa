<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use App\Subscription;
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

        $this->get($user->path())->assertSee($user);

    }

    /* This should be tested in VueJS */
    public function form_is_available_to_update_users()
    {
    }

    /** @test */
    public function users_can_register_into_the_system()
    {
        $this->withoutExceptionHandling();
        $subscription = factory('App\Subscription')->create();
        $role = factory('App\Role')->create();
        $attributes = [
            'id' => 1,
            'subscription_id' => $subscription->id,
            'role_id' => $role->id,
            'name' => 'Hamed Afshar',
            'email' => 'asghar@yahoo.com',
            'password' => '13651362',
            'password_confirmation' => '13651362',
            'language' => 'Persian',
            'tel' => '989123463474',
            'country' => 'Iran',
            'communication_media' => 'telegram'
        ];
        $this->post('/register', $attributes);
        $this->assertDatabaseHas('users', ['name' => 'Hamed Afshar']);
    }

    /** @test */
    public function users_can_update_their_profiles()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'edit-profile', 0, 1);
        $newDetails = factory('App\User')->raw();
        $user = User::find(1);
        $this->patch('/edit-profile/' . $user->id, [
            'name' => $newDetails['name'],
            'email' => $newDetails['email'],
            'password' => $newDetails['password'],
            'password_confirmation' => $newDetails['password'],
            'language' => $newDetails['language'],
            'tel' => $newDetails['tel'],
            'country' => $newDetails['country'],
            'communication_media' => $newDetails['communication_media']
        ]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function users_can_not_update_others_profile()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $user1 = User::find(1);
        $user2 = factory('App\User')->create();
        $this->patch('/edit-profile/' . $user2->id, [
            'name' => 'new-name',
            'email' => 'new-email@yahoo.com',
            'password' => '123456789',
            'password_confirmation' => '123456789',
            'language' => 'english',
            'tel' => '989122035389',
            'country' => 'Turkey',
            'communication_media' => 'whatsapp'
        ])->assertForbidden();
    }

    /** @test */
    public function users_can_not_be_deleted_from_system()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $user = User::find(1);
        $this->delete($user->path())->assertForbidden();
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
