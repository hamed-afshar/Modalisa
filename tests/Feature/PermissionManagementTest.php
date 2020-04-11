<?php

namespace Tests\Feature;

use App\RolePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Permission;
use App\Role;

class PermissionManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_see_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-permissions', 1, 0);
        $permission = Permission::find(1);
        $this->get('/permissions')->assertSee($permission->id);
    }

    /** @test */
    public function form_is_available_to_create_permission()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-permissions', 1, 0);
        $this->get('/permissions/create')->assertOk();
    }

    /** @test */
    public function only_SystemAdmin_can_create_a_permission()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-permissions', 1, 0);
        $attributes = factory('App\Permission')->raw();
        $this->post('/permissions', $attributes);
        $this->assertDatabaseHas('permissions', $attributes);

    }

    /** @test */
    public function name_is_required()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'create-permissions', 1, 0);
        $attributes = factory('App\Permission')->raw(['name' => '']);
        $this->post('/permissions', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function only_SystemAdmin_can_view_a_single_permission()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-permissions', 1, 0);
        $permission = Permission::find(1);
        $this->get($permission->path())->assertSee($permission->name);
    }

    /** @test */
    public function form_is_available_to_edit_a_permission()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $permission = Permission::find(1);
        $this->get($permission->path() . '/edit')->assertSee($permission->name);

    }

    /** @test */
    public function only_SystemAdmin_can_edit_a_permission()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $permission = Permission::find(1);
        $newAttributes = [
            'name' => 'New Name'
        ];
        $this->patch($permission->path(), $newAttributes);
        $this->assertEquals($newAttributes['name'], Permission::where('id', $permission->id)->value('name'));
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_permission()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'delete-permissions', 1, 0);
        $permission = Permission::find(1);
        $this->delete($permission->path());
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    /** @test */
    public function a_role_can_have_many_permissions()
    {
        $rolePermission = factory('App\RolePermission')->create();
        $this->assertInstanceOf('App\Role', $rolePermission->roleOwner);
    }

    /** @test */
    public function a_permission_can_be_assigned_to_many_roles()
    {
        $rolePermission = factory('App\RolePermission')->create();
        $this->assertInstanceOf('App\Permission', $rolePermission->permissionOwner);
    }

    /** @test */
    public function only_SystemAdmin_can_see_all_permissions_assigned_to_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $rolePermission = factory('App\RolePermission')->create();
        $this->get('role/permissions')->assertSeeTextInOrder(array($rolePermission->id, $rolePermission->user_id, $rolePermission->permission_id));
    }

    /** @test */
    public function form_is_available_to_assigned_a_permission_to_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $this->get('/role/permissions')->assertOk();
    }

    /** @test */
    public function System_admin_can_assigned_a_permission_to_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $attributes = factory('App\RolePermission')->raw();
        $this->post('/role/permissions', $attributes)->assertRedirect('/role/permissions');
        $this->assertDatabaseHas('role_permissions', $attributes);
    }

    /** @test */
    public function role_id_is_required()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $attributes = factory('App\RolePermission')->raw(['role_id' => '']);
        $this->post('/role/permissions', $attributes)->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function permission_id_is_required()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $attributes = factory('App\RolePermission')->raw(['permission_id' => '']);
        $this->post('/role/permissions', $attributes)->assertSessionHasErrors('permission_id');
    }

    /** @test */
    public function System_admin_can_see_a_single_permission_assigned_to_role()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        factory('App\RolePermission')->create();
        $rolePermission = RolePermission::find(1);
        $this->get($rolePermission->path())->assertSeeTextInOrder(array($rolePermission->role_id, $rolePermission->permission_id));
    }

    /** @test */
    public function edit_form_is_available_to_update_a_permission_assigned_to_role()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        factory('App\RolePermission')->create();
        $rolePermission = RolePermission::find(1);
        $this->get($rolePermission->path() . '/edit')->assertSeeText($rolePermission->id);
    }

    /** @test */
    public function SystemAdmin_can_update_a_permission_assigned_to_role()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $rolePermission = factory('App\RolePermission')->create();
        $newRole = factory('App\Role')->create(['id' => 30]);
        $newPermission = factory('App\Permission')->create(['id' => 40]);
        $this->patch($rolePermission->path(), [
            'role_id' => $newRole->id,
            'permission_id' => $newPermission->id
        ])->assertRedirect($rolePermission->path());
        $this->assertEquals(30, RolePermission::where('id', $rolePermission->id)->value('role_id'));
        $this->assertEquals(40, RolePermission::where('id', $rolePermission->id)->value('permission_id'));
    }

    /** @test */
    public function SystemAdmin_can_delete_a_permission_assigned_to_role()
    {
        $this->withoutExceptionHandling();
        $this->prepare_SystemAdmin_env('SystemAdmin', 'edit-permissions', 1, 0);
        $rolePermission = factory('App\RolePermission')->create();
        $this->delete($rolePermission->path())->assertRedirect('/role/permissions');
        $this->assertDatabaseMissing('role_permissions', ['id' => $rolePermission->id]);
    }

    /** @test */
    public function other_users_can_not_access_permission_management()
    {
        $this->prepare_other_users_env('retailer', 'submit-orders',1,0);
        $permission = factory('App\Permission')->create();
        $rolePermission = factory('App\RolePermission')->create();
        $this->get('/permissions')->assertRedirect('/access-denied');
        $this->get('/permissions/create')->assertRedirect('/access-denied');
        $this->post('/permissions')->assertRedirect('/access-denied');
        $this->get($permission->path())->assertRedirect('/access-denied');
        $this->get($permission->path() .'/edit')->assertRedirect('/access-denied');
        $this->patch($permission->path())->assertRedirect('/access-denied');
        $this->delete($permission->path())->assertRedirect('/access-denied');

        $this->get('/role/permissions')->assertRedirect('/access-denied');
        $this->get('/role/permissions/create')->assertRedirect('/access-denied');
        $this->post('/role/permissions')->assertRedirect('/access-denied');
        $this->get($rolePermission->path())->assertRedirect('/access-denied');
        $this->get($rolePermission->path() .'/edit')->assertRedirect('/access-denied');
        $this->patch($rolePermission->path())->assertRedirect('/access-denied');
        $this->delete($rolePermission->path())->assertRedirect('/access-denied');
    }

    /** @test */
    public function guests_can_not_access_permission_management()
    {
        $permission = factory('App\Permission')->create();
        $rolePermission = factory('App\RolePermission')->create();
        $this->get('/permissions')->assertRedirect('login');
        $this->get('/permissions/create')->assertRedirect('login');
        $this->post('/permissions')->assertRedirect('login');
        $this->get($permission->path())->assertRedirect('login');
        $this->get($permission->path() . '/edit')->assertRedirect('login');
        $this->patch($permission->path())->assertRedirect('login');
        $this->delete($permission->path())->assertRedirect('login');

        $this->get('/role/permissions')->assertRedirect('login');
        $this->get('/role/permissions/create')->assertRedirect('login');
        $this->post('/role/permissions')->assertRedirect('login');
        $this->get($rolePermission->path())->assertRedirect('login');
        $this->get($rolePermission->path() . '/edit')->assertRedirect('login');
        $this->patch($rolePermission->path())->assertRedirect('login');
        $this->delete($rolePermission->path())->assertRedirect('login');
    }
}
