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

class AccessManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function prepare_SystemAdmin_env($role, $request, $confirmed, $locked)
    {
        $user = factory('App\User')->create(['id' => '1', 'confirmed' => $confirmed, 'locked' => $locked]);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->roles()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->assignedPermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

    public function prepare_other_users_env($role, $request, $confirmed, $locked)
    {
        $user = factory('App\User')->create(['id' => '1', 'confirmed' => $confirmed, 'locked' => $locked]);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->roles()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->assignedPermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

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
        $this->post('/roles', $attributes);
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
    public function only_SystemAdmin_can_edit_a_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-roles', 1, 0);
        $role = Role::find(1);
        $newAttributes = [
            'name' => 'New Name'
        ];
        $this->patch($role->path(), $newAttributes);
        $this->assertEquals($newAttributes['name'], Role::where('id', $role->id)->value('name'));
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'delete-roles', 1, 0);
        $role = Role::find(1);
        $this->delete($role->path());
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }


    /** @test */
    public function other_users_can_not_access_role_management()
    {
        $this->prepare_other_users_env('retailer', 'submit-orders',1,0);
        $attributes = factory('App\Role')->raw();
        $this->post('/roles', $attributes)->assertRedirect('/access-denied');
        $this->get('/roles/create')->assertRedirect('/access-denied');
        $this->get('/roles')->assertRedirect('/access-denied');
        $this->get('/roles/1')->assertRedirect('/access-denied');
        $this->get('/roles/1/edit')->assertRedirect('/access-denied');
    }

    /** @test */
    public function guests_can_not_access_role_management()
    {
        $this->post('/roles')->assertRedirect('login');
        $this->get('/roles/create')->assertRedirect('login');
        $this->get('/roles')->assertRedirect('login');
        $this->get('/roles/1')->assertRedirect('login');
        $this->get('/roles/1/edit')->assertRedirect('login');
    }
}
