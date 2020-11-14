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

    /** @test
     * only SystemAdmin can make changes to the permissions
     * other users are not allowed to make any changes including
     * index, create, store, show, update and delete
     */

    public function other_users_can_not_make_changes_to_the_permissions()
    {

        $this->prepNormalEnv('retailer', 'make-payment', 0,1);
        $permission = factory('App\Permission')->create();
        $newAttributes = factory('App\Permission')->raw();
        $this->get($permission->path())->assertForbidden();
        $this->get('permissions/create')->assertForbidden();
        $this->post('/permissions', $newAttributes)->assertForbidden();
        $this->get($permission->path())->assertForbidden();
        $this->get($permission->path() . '/edit')->assertForbidden();
        $this->patch($permission->path(), $newAttributes)->assertForbidden();
        $this->delete($permission->path())->assertForbidden();

    }

    /** @test */
    public function only_SystemAdmin_can_see_permissions()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $this->get('/permissions')->assertSeeText($permission->name);
    }

    /*
     * this should be tested in VueJs
     */
    public function form_is_available_to_create_permission()
    {

    }

    /** @test */
    public function only_SystemAdmin_can_create_a_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Permission')->raw();
        $this->post('/permissions', $attributes);
        $this->assertDatabaseHas('permissions', $attributes);
    }

    /** @test */
    public function name_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Permission')->raw(['name' => '']);
        $this->post('/permissions', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function label_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Permission')->raw(['label' => '']);
        $this->post('/permissions', $attributes)->assertSessionHasErrors('label');
    }

    /*
     * This test is not necessary
     */
    public function only_SystemAdmin_can_view_a_single_permission()
    {

    }

    /*
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_a_permission()
    {

    }

    /** @test */
    public function only_SystemAdmin_can_update_a_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $newAttributes = [
            'name' => 'New Name',
            'label' => 'New Label',
        ];
        $this->patch($permission->path(), $newAttributes);
        $this->assertDatabaseHas('permissions', $newAttributes);
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $this->delete($permission->path());
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    /** @test
     * many to many relationship
     */
    public function role_belongs_to_many_permissions()
    {
        $this->withoutExceptionHandling();
        $role = factory('App\Role')->create();
        $permission = factory('App\Permission')->create();
        $role->allowTo($permission);
        $this->assertInstanceOf(Permission::class, $role->permissions->find(1));
    }

    /** @test
     * many to many relationship
     */
    public function permission_belongs_to_many_roles()
    {
        $role = factory('App\Role')->create();
        $permission = factory('App\Permission')->create();
        $role->allowTo($permission);
        $this->assertInstanceOf(Role::class, $permission->roles->find(1));
    }

    /** @test */
    public function guests_can_not_access_permission_management()
    {
        $permission = factory('App\Permission')->create();
        $this->get('/permissions')->assertRedirect('login');
        $this->get('/permissions/create')->assertRedirect('login');
        $this->post('/permissions')->assertRedirect('login');
        $this->get($permission->path())->assertRedirect('login');
        $this->get($permission->path() . '/edit')->assertRedirect('login');
        $this->patch($permission->path())->assertRedirect('login');
        $this->delete($permission->path())->assertRedirect('login');
    }
}
