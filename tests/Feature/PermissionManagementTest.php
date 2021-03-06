<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Permission;
use App\Role;

class PermissionManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_see_permissions()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $this->get('/permissions')->assertSeeText($permission->name);
        //other users are not allowed to see permissions
        $this->prepNormalEnv('retailer', ['create-orders', 'create-transactions'] , 0,1);
        $this->get('/permissions')->assertForbidden();
    }

    /**
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
        //other users are not allowed to see permissions
        $this->prepNormalEnv('retailer', ['create-orders', 'create-transactions'] , 0,1);
        $this->post('/permissions', $attributes)->assertForbidden();
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

    /**
     * This test is not necessary
     */
    public function only_SystemAdmin_can_view_a_single_permission()
    {

    }

    /**
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
        //other users are not allowed to update permissions
        $this->prepNormalEnv('retailer', ['create-orders', 'create-transactions'] , 0,1);
        $this->patch($permission->path())->assertForbidden();
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['create-orders', 'create-transactions'] , 0,1);
        //other users are not allowed to delete permissions
        $retailer = Auth::user();
        $this->actingAs($retailer);
        $permission = factory('App\Permission')->create();
        $this->delete($permission->path())->assertForbidden();
        //only SystemAdmin can delete permissions
        $this->actingAs($SystemAdmin);
        $this->delete($permission->path());
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    /** @test
     * many to many relationship
     */
    public function each_role_belongs_to_many_permissions()
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
    public function each_permission_belongs_to_many_roles()
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
