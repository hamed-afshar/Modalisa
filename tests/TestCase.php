<?php

namespace Tests;

use App\Permission;
use App\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /*
     * prepare administrator environment
     */

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

    /*
     * prepare normal user environment
     */

    protected function prepNormalEnv($role, $permission, $locked, $confirmed)
    {
        $subscription = factory('App\Subscription')->create();
        $role = factory('App\Role')->create(['name' => $role]);
        $permission = factory('App\Permission')->create(['name' => $permission]);
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
        $role->changeRole($user);
        $role->allowTo($permission);
        $this->actingAs($user);
    }

    /*
     * prepare retailer environment
     */

    protected function prepRetailerEnv($role, $permission, $locked, $confirmed)
    {
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
        $role = factory('App\Role')->create(['name' => $role]);
        $permission = factory('App\Permission')->create(['name' => $permission]);
        $user->assignRole($role);
        $role->allowTo($permission);
        $this->actingAs($user);
    }
}
