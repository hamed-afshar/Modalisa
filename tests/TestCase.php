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

    /**
     * create order and product
     */

    protected function prepOrder()
    {
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $order = factory('App\Order')->create([
            'user_id' => Auth::user()->id,
            'customer_id' => $customer->id
        ]);
        $kargo = factory('App\Kargo')->create(['user_id' => Auth::user()->id]);
        $product = factory('App\Product')->create([
            'order_id' => $order->id,
            'kargo_id' => $kargo->id
        ]);
    }

}
