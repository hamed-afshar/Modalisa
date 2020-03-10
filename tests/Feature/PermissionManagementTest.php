<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Permission;
use App\Role;

class PermissionManagementTest extends TestCase
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

    /** @test */
    public function only_SystemAdmin_can_see_roles()
    {
        $this->prepare_SystemAdmin_env('SystemAdmin', 'see-permissions', 1, 0);
        $permission = Permission::find(1);
        $this->get('/permissions')->assertSee($permission->id);
    }

    /** @test  */
    public function  form_is_available_to_create_permission()
    {
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
}
