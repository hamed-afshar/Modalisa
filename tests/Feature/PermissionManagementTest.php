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
    public function only_SystemAdmin_can_see_permissions()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $this->get('/permissions')->assertSee($permission->id)
            ->assertStatus(200);
    }

    /** @test */
    public function form_is_available_to_create_permission()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $this->get('/permissions/create')->assertOk();
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

    /** @test */
    public function only_SystemAdmin_can_view_a_single_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $this->get('/permissions/1')->assertSee($permission->name);
    }

    /** @test */
    public function form_is_available_to_edit_a_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $this->get($permission->path() . '/edit')->assertSee($permission->name);

    }

    /** @test */
    public function only_SystemAdmin_can_edit_a_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $newAttributes = [
            'name' => 'New Name'
        ];
        $this->patch($permission->path(), $newAttributes);
        $this->assertEquals($newAttributes['name'], Permission::where('id', $permission->id)->value('name'));
    }

    /** @test */
    public function only_SystemAdmin_can_delete_a_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $permission = factory('App\Permission')->create();
        $this->delete($permission->path());
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    /** @test */
    public function role_belongs_to_many_permissions()
        //many to many relationship
    {
        $role = factory('App\Role')->create();
        $permission = factory('App\Permission')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $role->permissions);
    }

    /** @test */
    public function permission_belongs_to_many_permissions()
        //many to many relationship
    {
        $role = factory('App\Role')->create();
        $permission = factory('App\Permission')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $permission->roles);
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
