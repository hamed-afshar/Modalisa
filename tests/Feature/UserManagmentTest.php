<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;

class UserManagmentTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    public function prepare_SystemAdmin_env($role, $request)
    {
        $user = factory('App\User')->create(['id' => '1']);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->roles()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->assignedPermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

    public function prepare_other_users_env($role, $request)
    {
        $user = factory('App\User')->create(['id' => '1']);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->roles()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->assignedPermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

    /** @test */
    public function only_SystemAdmin_can_see_users()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-users');
        $user = User::find(1);
        $this->get('/users')->assertSee($user->name);
    }

    /** @test */
    public function form_is_available_to_create_user()
    {
        $this->withoutExceptionHandling();
        $this->get('/users/create')->assertOk();
    }

    /** @test */
    public function users_can_register_in_system()
    {
        $this->withoutExceptionHandling();
        $attributes = factory('App\User')->raw();
        $this->post('/users', $attributes)->assertRedirect('/pending-for-confirmation');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function see_pending_for_confirmation_after_user_registration()
    {
        $this->withoutExceptionHandling();
        $this->get('/pending-for-confirmation')->assertOk(200);
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
        $this->prepare_SystemAdmin_env('SystemAdmin','see-users');
        $user = User::find(1);
        $this->get($user->path())->assertSee($user->name);
        $this->assertInstanceOf(Carbon::class, $user->last_login);
        $this->assertInstanceOf(Carbon::class, $user->created_at);
        $this->assertInstanceOf(Carbon::class, $user->updated_at);
    }

    /** @test */
    public function form_is_available_to_edit_a_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-users');
        $user = User::find(1);
        $this->get($user->path() . '/edit')->assertSee($user->name);
    }

    /** @test */
    public function just_SystemAdmin_can_edit_users()
    {
        $user = factory('App\User')->create();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $this->patch('/users/' . $user->id, [
            'confirmed' => 1,
            'access_level' => 'BuyerAdmin',
            'lock' => 0
        ]);
        $this->assertEquals(1, DB::table('users')->where('id', $user->id)->value('confirmed'));
        $this->assertEquals('BuyerAdmin', DB::table('users')->where('id', $user->id)->value('access_level'));
        $this->assertEquals(0, DB::table('users')->where('id', $user->id)->value('lock'));
    }


    /** @test */
    public function other_users_can_not_access_user_management()
    {
        $this->withoutExceptionHandling();
        $this->prepare_other_users_env('retailer', 'submit-orders');
        $this->get('/users')->assertRedirect('/access-denied');
    }

    /** @test */
    public function guest_can_not_access_user_management_system()
    {
        $this->get('/users')->assertRedirect('login');
    }

    /** @test */
    public function users_can_not_be_deleted_from_system()
    {
        $this->withoutExceptionHandling();
        $this->delete('/users')->assertRedirect('/access-denied');
    }

}
