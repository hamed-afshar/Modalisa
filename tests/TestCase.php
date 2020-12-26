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
        factory('App\Subscription')->create([
            'plan' => 'Basic',
            'cost_percentage' => 30
        ]);
        $role = factory('App\Role')->create(['name' => $role]);
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
        $role->changeRole($user);
        $this->actingAs($user);
    }

    /**
     * prepare normal user environment
     * @param $role
     * @param $permissions[]
     * @param $locked
     * @param $confirmed
     */

    protected function prepNormalEnv($role, $permissions, $locked, $confirmed)
    {
        //get all existing permissions from db
        $currentPermissions = Permission::all();
        factory('App\Subscription')->create();
        $role = factory('App\Role')->create(['name' => $role]);
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked]);
        $role->changeRole($user);
        foreach($permissions as $permission) {
            if(in_array($permission, $currentPermissions->toArray())) {

            } else {
                $permission = factory('App\Permission')->create(['name' => $permission]);
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
