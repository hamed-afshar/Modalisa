<?php

namespace Tests;

use App\Permission;
use App\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * prepare administrator environment
     */

    protected function prepAdminEnv($role, $locked, $confirmed)
    {
        $role = factory('App\Role')->create(['name' => $role]);
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
        $role->changeRole($user);
        $this->actingAs($user);
    }

    /**
     * prepare normal user environment
     * @param $role
     * @param $permissions []
     * @param $lockedc
     * @param $confirmed
     */

    protected function prepNormalEnv($role, $permissionArray, $locked, $confirmed)
    {
        factory('App\Subscription')->create();
        $role = factory('App\Role')->create(['name' => $role]);
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
        $role->changeRole($user);
        foreach ($permissionArray as $permissionName) {
            // if permission already has existed in db then permission will not be created and assign to the role
            if (Permission::where('name', '=', $permissionName)->count() > 0)
            {
                $permission = Permission::where('name', '=', $permissionName)->get();
                $role->allowTo($permission);
            }
            //if permission has not existed in db then new permission will be created and assign to the role
            else {
                $permission = factory('App\Permission')->create(['name' => $permissionName]);
                $role->allowTo($permission);
            }
        }
        $this->actingAs($user);
    }

    /**
     * create order and product
     */

    protected function prepOrder()
    {
        factory('App\Status')->create();
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $order = factory('App\Order')->create([
            'user_id' => Auth::user()->id,
            'customer_id' => $customer->id
        ]);
        $kargo = factory('App\Kargo')->create(['user_id' => Auth::user()->id]);
        factory('App\Product')->create([
            'order_id' => $order->id,
            'kargo_id' => $kargo->id
        ]);
    }

}
