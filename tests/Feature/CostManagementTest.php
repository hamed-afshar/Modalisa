<?php

namespace Tests\Feature;

use App\Cost;
use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CostManagementTest extends TestCase
{
   use RefreshDatabase, WithFaker;

   public function only_buyeradmins_can_create_costs()
   {

   }

    /** @test
     * for user model
     */
    public function each_user_can_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order','costable_id' =>$order->id ]);
        $this->assertInstanceOf(Cost::class, $user->costs->find(1));
    }
    /** @test
     * for Order model
     */


    /** @test
     * for Product model
     */
    /** @test
     * for Transaction model
     */
    /** @test
     * for Kargo model
     */
}
