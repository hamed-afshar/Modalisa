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
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $roles = Role::find(1);
        $this->get('/roles')->assertStatus(200)
            ->assertSeeText($roles->id);
    }

    /** @test */
    public function form_is_available_to_create_roles()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $this->get('/roles/create')->assertStatus(200);
    }

    /** @test */
    public function only_SystemAdmin_can_create_roles()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Role')->raw(['name' => 'retailer', 'label' => 'test']);
        $this->post('/roles', $attributes)->assertRedirect('/roles');
        $this->assertDatabaseHas('roles', $attributes);
    }

    /** @test */
    public function named_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Role')->raw(['name' => '']);
        $this->post('/roles', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function label_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $attributes = factory('App\Role')->raw(['label' => '']);
        $this->post('/roles', $attributes)->assertSessionHasErrors('label');
    }

    /** @test */
    public function only_SystemAdmin_can_vew_a_single_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $this->get($role->path())->assertSee($role->name);
    }

    /** @test */
    public function form_is_available_to_edit_a_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $this->get($role->path() . '/edit')->assertSee($role->name);
    }

    /** @test */
    public function only_SystemAdmin_can_update_a_role()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
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
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $this->delete($role->path())->assertRedirect('/roles');
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    /** @test */
    //many to many relationship
    public function user_belongs_to_many_roles()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->roles);
    }

    /** @test */
    //many to many relationship
    public function role_belongs_to_many_users()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $role->users);
    }

    /** @test */
    //SystemAdmin can view all permissions associated to a role
    public function SystemAdmin_can_view_permissions_associated_to_a_role()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $this->get('/granted-permissions/' . $role->id)->assertOk();
    }

    /** @test */
    public function SystemAdmin_can_allow_role_to_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        $permission = factory('App\Permission')->create();
        $permission = Permission::find(1);
        $this->post('/allow-to/' . $role->id . '/' . $permission->id)->assertOk();
        $this->assertDatabaseHas('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
    }

    /** @test */
    public function SystemAdmin_can_disallow_role_to_permission()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $role = Role::find(1);
        factory('App\Permission')->create();
        $permission = Permission::find(1);
        $role->allowTo($permission);
        $this->delete('/disallow-to/' . $role->id . '/' . $permission->id)->assertOk();
        $this->assertDatabaseMissing('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
    }


    /** @test */
    public function guests_can_not_access_role_management()
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
