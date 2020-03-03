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

    public function prepare_SystemAdmin_env()
    {
        $user = factory('App\User')->create(['id' => '1']);
        $role = Role::create(['id' => 1, 'name' => 'SystemAdmin']);
        $permission = Permission::create(['id' => 1, 'name' => 'create-role']);
        $userRole = $user->roles()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->assignedPermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }
    public function prepare_other_users_env()
    {
        $user = factory('App\User')->create(['id' => '1']);
        $role = Role::create(['id' => 1, 'name' => 'Retailer']);
        $permission = Permission::create(['id' => 1, 'name' => 'submit-order']);
        $userRole = $user->roles()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->assignedPermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

    /** @test */
    public function only_SystemAdmin_can_create_role()
    {
        $this->prepare_SystemAdmin_env();
        $attributes = factory('App\Role')->raw();
        $this->post('/roles', $attributes);
        $this->assertDatabaseHas('roles', $attributes);
    }


    /** @test */
    public function other_users_can_not_access_role_management()
    {
        $this->withoutExceptionHandling();
        $this->prepare_other_users_env();
        $role = factory('App\Role')->raw();
        $this->post('/roles', $role)->assertRedirect('/access-denied');
    }

    /** @test */
    public function guests_can_not_access_role_management()
    {
        $this->post('/role')->assertRedirect('login');
    }
}
