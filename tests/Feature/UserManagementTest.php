<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\User;

class UserManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_see_users_with_respective_role_and_subscription()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['create-costs', 'see-costs'], 0 , 1);
        $retailer = Auth::user();
        //System admin is able to see all users
        $this->actingAs($SystemAdmin, 'api');
        $this->get('api/users')
            ->assertSeeText($SystemAdmin->name)
            ->assertSeeText($retailer->name)
            ->assertSeeText($retailer->role->name)
            ->assertSeeText($retailer->subscription->plan);
        //other users are not allowed to see users list
        $this->actingAs($retailer, 'api');
        $this->get('api/users')->assertForbidden();
    }

    /** @test */
    public function user_registration_form_is_available()
    {
        $this->withoutExceptionHandling();
        $this->get('/register')->assertOk();
    }

    /** @test */
    public function users_can_register_into_the_system()
    {
        $this->withoutExceptionHandling();
        $attributes = [
            'id' => 1,
            'name' => 'Hamed Afshar',
            'email' => 'asghar@yahoo.com',
            'password' => '13651362',
            'password_confirmation' => '13651362',
            'language' => 'Persian',
            'tel' => '989123463474',
            'country' => 'Iran',
            'communication_media' => 'telegram'
        ];
        $this->post('api/register', $attributes);
        $this->assertDatabaseHas('users', ['name' => 'Hamed Afshar']);
    }

    /** @test */
    public function only_SystemAdmin_can_vew_a_single_user()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['create-costs', 'see-costs'], 0 , 1);
        $retailer = Auth::user();
        //System admin is able to see all users
        $this->actingAs($SystemAdmin, 'api');
        $this->get($retailer->path())->assertSeeText($retailer->name);
        //other users are not allowed to see users list
        $this->actingAs($retailer, 'api');
        $this->get($retailer->path())->assertForbidden();
    }

    /** @test */
    public function users_can_update_their_own_profiles()
    {
        $this->prepNormalEnv('retailer', ['edit-profile', 'create-costs'], 0, 1);
        $retailer1 = Auth::user();
        $newAttributes = [
            'name' => 'amin test',
            'language' => 'Turkey',
            'tel' => '989122211334',
            'country' => 'england',
            'communication_media' => 'whatsapp'
        ];
        $this->actingAs($retailer1, 'api');
        $this->post('api/edit-profile/' . $retailer1->id, $newAttributes);
        $user = User::find(1);
        //assert to see user record is updated
        $this->assertEquals($newAttributes['name'], $user->name);
        $this->prepNormalEnv('retailer2', ['edit-profile', 'create-costs'], 0, 1);
        //users can only update their own records
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->post('api/edit-profile/' . $retailer1->id, $newAttributes)->assertForbidden();
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
