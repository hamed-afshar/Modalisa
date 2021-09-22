<?php

namespace Tests;

use App\Permission;
use App\Role;
use App\Status;
use App\Traits\HistoryTrait;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, HistoryTrait;

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
     * @param $permissionArray
     * @param $locked
     * @param $confirmed
     */

    protected function prepNormalEnv($role, $permissionArray, $locked, $confirmed)
    {
        $subscription = factory('App\Subscription')->create();
        $role = factory('App\Role')->create(['name' => $role]);
        $user = factory('App\User')->create(['confirmed' => $confirmed, 'locked' => $locked, 'subscription_id' => $subscription->id]);
        $role->changeRole($user);
        foreach ($permissionArray as $permissionName) {
            // if permission already has existed in db then permission will not be created and assign to the role
            if (Permission::where('name', '=', $permissionName)->count() > 0) {
                $permission = Permission::where('name', '=', $permissionName)->get();
                $role->allowTo($permission);
            } //if permission has not existed in db then new permission will be created and assign to the role
            else {
                $permission = factory('App\Permission')->create(['name' => $permissionName]);
                $role->allowTo($permission);
            }
        }
        $this->actingAs($user);
    }

    /**
     * create order and product
     * @param $withKargo
     * @param $withoutKargo
     */
    protected function prepOrder($withKargo, $withoutKargo)
    {
        //create first two possible statuses. 0:Order Deleted, 1:Order Created
//        factory('App\Status', 2)->create();
        factory('App\Status')->create([
            'priority' => 1,
            'name' => 'Order Deleted',
            'description' => 'Order Deleted',
        ]);
        factory('App\Status')->create([
            'priority' => 2,
            'name' => 'Order Created',
            'description' => 'Order Created',
        ]);
        factory('App\Status')->create([
            'priority' => 3,
            'name' => 'Order Bought',
            'description' => 'Order has Bought',
        ]);
        factory('App\Status')->create([
            'priority' => 4,
            'name' => 'Order In-Office',
            'description' => 'Order is in office',
        ]);
        factory('App\Status')->create([
            'priority' => 5,
            'name' => 'Order In Kargo To Destination',
            'description' => 'Order is in kargo to the destination',
        ]);
        factory('App\Status')->create([
            'priority' => 6,
            'name' => 'Order In Destination',
            'description' => 'Order is in destination',
        ]);
        factory('App\Status')->create([
            'priority' => 7,
            'name' => 'Order IN Kargo From Destination',
            'description' => 'Order is in kargo from the destination to return',
        ]);
        factory('App\Status')->create([
            'priority' => 8,
            'name' => 'Order Returned',
            'description' => 'Order has returned to the seller',
        ]);
        factory('App\Status')->create([
            'priority' => 9,
            'name' => 'Order Refunded',
            'description' => 'Payment for the order has refunded',
        ]);
        factory('App\Status')->create([
            'priority' => 10,
            'name' => 'Order Edited',
            'description' => 'Order has edited',
        ]);
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $order = factory('App\Order')->create([
            'user_id' => Auth::user()->id,
            'customer_id' => $customer->id
        ]);
        $boughtStatus = Status::find(3);
        $inOfficeStatus = Status::find(4);
        for ($i = 0; $i < $withoutKargo; $i++) {
            $p = factory('App\Product')->create([
                'order_id' => $order->id,
            ]);
            $this->storeHistory($p, $boughtStatus);
            $this->storeHistory($p, $inOfficeStatus);
        }
        if ($withKargo > 0) {
            $kargo = factory('App\Kargo')->create(['user_id' => Auth::user()->id]);
            for($i=0; $i<$withKargo; $i++) {
                $p = factory('App\Product')->create([
                    'order_id' => $order->id,
                    'kargo_id' => $kargo->id
                ]);
                $this->storeHistory($p, $boughtStatus);
                $this->storeHistory($p, $inOfficeStatus);
            }
        }
    }

    /**
     * create remaining possible statuses in the system
     */
    public function prepStatus()
    {
        factory('App\Status')->create([
            'priority' => 3,
            'name' => 'Order Bought',
            'description' => 'order is bought'
        ]);
        factory('App\Status')->create([
            'priority' => 4,
            'name' => 'Order In-Office',
            'description' => 'order reached to office in Turkey'
        ]);
        factory('App\Status')->create([
            'priority' => 5,
            'name' => 'Order In-Kargo-To-Iran',
            'description' => 'order is in kargo to Iran'
        ]);
        factory('App\Status')->create([
            'priority' => 6,
            'name' => 'Order In-Iran',
            'description' => 'order reached to Iran'
        ]);
        factory('App\Status')->create([
            'priority' => 7,
            'name' => 'Order In-Kargo-From-Iran',
            'description' => 'order returned back from Iran'
        ]);
        factory('App\Status')->create([
            'priority' => 8,
            'name' => 'Order Returned',
            'description' => 'order is returned to seller'
        ]);
        factory('App\Status')->create([
            'priority' => 9,
            'name' => 'Order Refunded',
            'description' => 'order is refunded'
        ]);
        factory('App\Status')->create([
            'priority' => 10,
            'name' => 'Order Edited',
            'description' => 'order is edited',
        ]);
    }
}
