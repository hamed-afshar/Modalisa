<?php

namespace Tests;

use App\Permission;
use App\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function prepare_SystemAdmin_env($role, $request, $confirmed, $locked)
    {
        $user = factory('App\User')->create(['id' => '1', 'confirmed' => $confirmed, 'locked' => $locked]);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->role()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->rolePermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }

    protected function prepare_other_users_env($role, $request, $confirmed, $locked)
    {
        $user = factory('App\User')->create(['id' => '1', 'confirmed' => $confirmed, 'locked' => $locked]);
        $role = Role::create(['id' => 1, 'name' => $role]);
        $permission = Permission::create(['id' => 1, 'name' => $request]);
        $userRole = $user->role()->create(['user_id' => $user->id, 'role_id' => $role->id]);
        $rolePermission = $role->rolePermissions()->create(['role_id' => $role->id, 'permission_id' => $permission->id]);
        $this->actingAs($user);
    }
}
