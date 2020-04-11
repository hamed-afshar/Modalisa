<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use App\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /** @test */
    public function only_SystemAdmin_can_see_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-roles', 1, 0);
        $roles = Role::find(1);
        $this->get('/roles')->assertSee($roles->id);
    }

    /** @test */
    public function form_is_available_to_create_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-roles', 1,0);
        $this->get('/roles/create')->assertOk();
    }

    /** @test */
    public function only_SystemAdmin_can_create_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-roles', 1, 0);
        $attributes = factory('App\Role')->raw();
        $this->post('/roles', $attributes)->assertRedirect('/roles');
        $this->assertDatabaseHas('roles', $attributes);
    }

    /** @test */
    public function named_is_required()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-roles', 1, 0);
        $attributes = factory('App\Role')->raw(['name' => '']);
        $this->post('/roles', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function only_SystemAdmin_can_vew_a_single_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-roles', 1, 0);
        $role = Role::find(1);
        $this->get($role->path())->assertSee($role->name);
    }

    /** @test */
    public function form_is_available_to_edit_a_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $role = Role::find(1);
        $this->get($role->path() . '/edit')->assertSee($role->name);
    }

    /** @test */
    public function only_SystemAdmin_can_update_a_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $role = Role::find(1);
        $newAttributes = [
            'name' => 'New Name'
        ];
        $this->patch($role->path(), $newAttributes)->assertRedirect($role->path());
        $this->assertEquals($newAttributes['name'], Role::where('id', $role->id)->value('name'));
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'delete-roles', 1, 0);
        $role = Role::find(1);
        $this->delete($role->path())->assertRedirect('/roles');
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    /** @test */
    public function UserRole_belongs_to_one_role()
    {
        $userRole = factory('App\UserRole')->create();
        $this->assertInstanceOf('App\Role', $userRole->roleOwner);
    }

    /** @test */
    public function UserRole_belongs_to_one_user()
    {
        $userRole = factory('App\UserRole')->create();
        $this->assertInstanceOf('App\User', $userRole->userOwner);
    }

    /** @test */
    public function SystemAdmin_can_see_all_roles_assigned_to_users()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $userRole = factory('App\UserRole')->create();
        $this->get('/user/roles')->assertSeeTextInOrder(array($userRole->id, $userRole->user_id, $userRole->role_id));
    }

    /** @test */
    public function form_is_available_to_assign_a_role_to_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $this->get('/user/roles/create')->assertOk();

    }

    /** @test */
    public function SystemAdmin_can_assigned_a_role_to_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $attributes = factory('App\UserRole')->raw();
        $this->post('/user/roles', $attributes)->assertRedirect('/user/roles');
        $this->assertDatabaseHas('user_roles', $attributes);
    }

    /** @test */
    public function user_id_is_required()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $attributes = factory('App\UserRole')->raw(['user_id' => '']);
        $this->post('/user/roles', $attributes)->assertSessionHasErrors('user_id');
    }

    /** @test */
    public function role_id_is_required()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $attributes = factory('App\UserRole')->raw(['role_id' => '']);
        $this->post('/user/roles', $attributes)->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function SystemAdmin_can_view_a_single_user_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $userRole = factory('App\UserRole')->create();
        $this->get($userRole->path())->assertSeeTextInOrder(array($userRole->id, $userRole->user_id, $userRole->role_id));
    }

    /** @test */
    public function form_is_available_to_edit_a_role_assigned_to_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $userRole = factory('App\UserRole')->create();
        $this->get($userRole->path().'/edit')->assertSeeTextInOrder(array($userRole->id, $userRole->user_id, $userRole->role_id));
    }

    /** @test */
    public function SystemAdmin_can_update_a_role_assigned_to_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $newUser = factory('App\User')->create(['id' => 15]);
        $newRole = factory('App\Role')->create(['id' => 25]);
        $userRole = factory('App\UserRole')->create();
        $newAttributes = [
            'user_id' => $newUser->id,
            'role_id' => $newRole->id
        ];
        $this->patch($userRole->path(), $newAttributes)->assertRedirect($userRole->path());
        $this->assertEquals($newAttributes['user_id'], UserRole::where('id', $userRole->id)->value('user_id'));
        $this->assertEquals($newAttributes['role_id'], UserRole::where('id', $userRole->id)->value('role_id'));
    }

    /** @test */
    public function SystemAdmin_can_delete_a_role_assigned_to_user()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $userRole = factory('App\UserRole')->create();
        $this->delete($userRole->path())->assertRedirect('/user/roles');
        $this->assertDatabaseMissing('user_roles', ['id' => $userRole->id]);
    }


    /** @test */
    public function other_users_can_not_access_role_management()
    {
        $this->prepare_other_users_env('retailer', 'submit-orders',1,0);
        $role = factory('App\Role')->create();
        $userRole = factory('App\UserRole')->create();
        $this->get('/roles')->assertRedirect('/access-denied');
        $this->get('/roles/create')->assertRedirect('/access-denied');
        $this->post('/roles')->assertRedirect('/access-denied');
        $this->get($role->path())->assertRedirect('/access-denied');
        $this->get($role->path() .'/edit')->assertRedirect('/access-denied');
        $this->patch($role->path())->assertRedirect('/access-denied');
        $this->delete($role->path())->assertRedirect('/access-denied');

        $this->get('/user/roles')->assertRedirect('/access-denied');
        $this->get('/user/roles/create')->assertRedirect('/access-denied');
        $this->post('/user/roles')->assertRedirect('/access-denied');
        $this->get($userRole->path())->assertRedirect('/access-denied');
        $this->get($userRole->path() .'/edit')->assertRedirect('/access-denied');
        $this->patch($userRole->path())->assertRedirect('/access-denied');
        $this->delete($userRole->path())->assertRedirect('/access-denied');

    }

    /** @test */
    public function guests_can_not_access_role_management()
    {
        $role = factory('App\Role')->create();
        $userRole = factory('App\UserRole')->create();
        $this->get('/roles')->assertRedirect('login');
        $this->get('/roles/create')->assertRedirect('login');
        $this->post('/roles')->assertRedirect('login');
        $this->get($role->path())->assertRedirect('login');
        $this->get($role->path() . '/edit')->assertRedirect('login');
        $this->patch($role->path())->assertRedirect('login');
        $this->delete($role->path())->assertRedirect('login');

        $this->get('/user/roles')->assertRedirect('login');
        $this->get('/user/roles/create')->assertRedirect('login');
        $this->post('/user/roles')->assertRedirect('login');
        $this->get($userRole->path())->assertRedirect('login');
        $this->get($userRole->path() . '/edit')->assertRedirect('login');
        $this->patch($userRole->path())->assertRedirect('login');
        $this->delete($userRole->path())->assertRedirect('login');
    }
}
