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

    /** @test
     * only SystemAdmin can make changes to the roles
     * other users are not allowed to make any changes including
     * index, create, store, show, update  delete
     */

    public function other_users_can_not_make_changes_to_the_roles()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $role = factory('App\Role')->create();
        $newAttributes = factory('App\Role')->raw();
        $this->get($role->path())->assertForbidden();
        $this->get($role->path() . '/create')->status(404);
        $this->post('/roles', $newAttributes)->assertForbidden();
        $this->get($role->path())->assertForbidden();
        $this->get($role->path() . '/edit')->status(404);
        $this->patch($role->path(), $newAttributes)->assertForbidden();
        $this->delete($role->path())->assertForbidden();

    }

    /** @test */
    public function SystemAdmin_can_see_all_permission_assigned_to_a_role()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0 , 1);
        $role = Role::find(1);
        $permission = Permission::find(1);
        $this->get($role->path())->assertSee($permission);
    }

    /** @test */
    public
    function only_SystemAdmin_can_see_roles()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $roles = Role::find(1);
        $this->get('/roles')->assertSeeText($roles->name);
    }

    /*
      * this should be tested in VueJs
      */
    public
    function form_is_available_to_create_roles()
    {

    }

    /** @test */
    public
    function only_SystemAdmin_can_create_roles()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Role')->raw(['name' => 'retailer', 'label' => 'test']);
        $this->post('/roles', $attributes);
        $this->assertDatabaseHas('roles', $attributes);
    }

    /** @test */
    public
    function named_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Role')->raw(['name' => '']);
        $this->post('/roles', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public
    function label_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Role')->raw(['label' => '']);
        $this->post('/roles', $attributes)->assertSessionHasErrors('label');
    }

    /*
     * This test is not necessary
     */
    public
    function only_SystemAdmin_can_vew_a_single_role()
    {

    }

    /*
     * this should be tested in VueJs
     */
    public function form_is_available_to_edit_a_role()
    {

    }

    /** @test */
    public function only_SystemAdmin_can_update_a_role()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $newAttributes = [
            'name' => 'New Name',
            'label' => 'New Label'
        ];
        $this->patch($role->path(), $newAttributes);
        $this->assertDatabaseHas('roles', $newAttributes);
    }

    /** @test */
    public
    function only_SystemAdmin_can_delete_a_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = factory('App\Role')->create();
        $this->delete($role->path());
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    /** @test
     * one to many relationship
     */
    public
    function role_have_many_users()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $this->assertInstanceOf(User::class, $role->users->find(1));
    }

    /** @test
     * one to many relationship
     */
    public
    function user_belongs_to_a_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $user = User::find(1);
        $this->assertInstanceOf(Role::class, $user->role);
    }


    /** @test */
    public
    function SystemAdmin_can_allow_role_to_permission()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        factory('App\Permission')->create();
        $permission = Permission::find(1);

        $this->get('/allow-to/' . $role->id . '/' . $permission->id);
        $this->assertDatabaseHas('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
    }

    /** @test */
    public
    function SystemAdmin_can_disallow_role_to_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        factory('App\Permission')->create();
        $permission = Permission::find(1);
        $role->allowTo($permission);
        $this->get('/disallow-to/' . $role->id . '/' . $permission->id);
        $this->assertDatabaseMissing('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
    }

    /** @test */
    public
    function SystemAdmin_can_change_user_roles()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $newUser = factory('App\User')->create();
        $newRole = factory('App\Role')->create(['name' => 'accountant']);
        $this->get('/change-role/' . $newRole->id . '/' . $newUser->id);
        $this->assertDatabaseHas('users', ['role_id' => $newRole->id]);
    }


    /** @test */
    public
    function guests_can_not_access_role_management()
    {
        $role = factory('App\Role')->create();
        $this->get('/roles')->assertRedirect('login');
        $this->get('/roles/create')->assertRedirect('login');
        $this->post('/roles')->assertRedirect('login');
        $this->get($role->path())->assertRedirect('login');
        $this->get($role->path() . '/edit')->assertRedirect('login');
        $this->patch($role->path())->assertRedirect('login');
        $this->delete($role->path())->assertRedirect('login');
    }
}
