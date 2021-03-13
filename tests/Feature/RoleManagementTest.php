<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_see_roles()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $roles = Role::find(1);
        $this->get('api/roles')->assertSeeText($roles->name);
        //other users are not able to see roles
        $this->prepNormalEnv('retailer', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get('api/roles')->assertForbidden();
    }


    /** @test */
    public
    function only_SystemAdmin_can_create_roles()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $attributes = factory('App\Role')->raw([
            'name' => 'retailer',
            'label' => 'test'
        ]);
        $this->post('api/roles', $attributes);
        $this->assertDatabaseHas('roles', $attributes);
        //other users are not allowed to create roles
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->post('api/roles', $attributes)->assertForbidden();
    }

    /** @test */
    public
    function named_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $attributes = factory('App\Role')->raw([
            'name' => '',
            'label' => 'test'
        ]);
        $this->post('api/roles', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public
    function label_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $attributes = factory('App\Role')->raw([
            'name' => 'retailer',
            'label' => '']);
        $this->post('api/roles', $attributes)->assertSessionHasErrors('label');
    }

    /** @test */
    public
    function only_SystemAdmin_can_vew_a_single_role_with_all_assigned_permissions()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $role = Role::find(1);
        $permission = factory('App\Permission')->create();
        $role->allowTo($permission);
        $this->get($role->path())->assertSeeText($permission->name);
        //other users are not allowed to see a single role
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get($role->path())->assertForbidden();
    }


    /** @test */
    public function only_SystemAdmin_can_update_a_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $role = Role::find(1);
        $newAttributes = [
            'name' => 'New Name',
            'label' => 'New Label'
        ];
        $this->patch($role->path(), $newAttributes);
        $this->assertDatabaseHas('roles', $newAttributes);
        //other users are not allowed to update roles
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get($role->path())->assertForbidden();
    }

    /** @test */
    public
    function only_SystemAdmin_can_delete_a_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $role = Role::find(1);
        //other users are not allowed to delete roles
        //acting as the retailer to delete the role
        $this->actingAs($retailer, 'api');
        $this->delete($role->path())->assertForbidden();
        //acting as the SystemAdmin to delete the role
        $this->actingAs($SystemAdmin, 'api');
        $this->delete($role->path());
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    /** @test */
    public
    function SystemAdmin_can_allow_role_to_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $role = Role::find(1);
        factory('App\Permission')->create();
        $permission = Permission::find(1);
        $this->post('api/allow-to/' . $role->id . '/' . $permission->id);
        $this->assertDatabaseHas('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
        //other users are not allowed to assign permissions to rols
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->post('api/allow-to/' . $role->id . '/' . $permission->id)->assertForbidden();
    }

    /** @test */
    public
    function SystemAdmin_can_disallow_roles_to_permissions()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $role = Role::find(1);
        factory('App\Permission')->create();
        $permission = Permission::find(1);
        $role->allowTo($permission);
        $this->post('api/disallow-to/' . $role->id . '/' . $permission->id);
        $this->assertDatabaseMissing('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
        //other users are not allowed to assign permissions to roles
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->post('api/disallow-to/' . $role->id . '/' . $permission->id)->assertForbidden();
    }

    /** @test */
    public
    function SystemAdmin_can_change_user_roles()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $oldUser = Auth::user();
        $newRole = factory('App\Role')->create(['name' => 'accountant']);
        $this->post('api/change-role/' . $newRole->id . '/' . $oldUser->id);
        $this->assertDatabaseHas('users', ['id' => $oldUser->id, 'role_id' => $newRole->id]);
        //other users are not allowed to assign permissions to roles
        $this->prepNormalEnv('retailer-assistant', ['create-order', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->post('api/change-role/' . $newRole->id . '/' . $oldUser->id)->assertForbidden();
    }

    /** @test
     * one to many relationship
     */
    public
    function each_role_have_many_users()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $this->assertInstanceOf(User::class, $role->users->find(1));
    }

    /** @test
     * one to many relationship
     */
    public
    function each_user_belongs_to_a_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $user = User::find(1);
        $this->assertInstanceOf(Role::class, $user->role);
    }

    /** @test */
    public
    function guests_can_not_access_role_management()
    {
        $role = factory('App\Role')->create();
        $this->get('api/roles')->assertRedirect('login');
        $this->post('api/roles')->assertRedirect('login');
        $this->get($role->path())->assertRedirect('login');
        $this->patch($role->path())->assertRedirect('login');
        $this->delete($role->path())->assertRedirect('login');
    }
}
