<?php

namespace Tests;

use App\Permission;
use App\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function prepAdminEnv($role, $locked, $confirmed)
    {
        factory('App\Subscription')->create([
            'plan' => 'Basic',
            'cost_percentage' => 30
        ]);
        $role = factory('App\Role')->create(['name' => $role]);
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
        $role->changeRole($user);
        $this->actingAs($user);
    }

    protected function prepNormalEnv($role, $permission, $locked, $confirmed)
    {
        {
            $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
            $role = factory('App\Role')->create(['name' => $role]);
            $permission = factory('App\Permission')->create(['name' => $permission]);
            $user->assignRole($role);
            $role->allowTo($permission);
            $this->actingAs($user);
        }
    }
}
