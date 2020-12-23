<?php

namespace Tests\Feature;

use App\Cost;
use App\Kargo;
use App\Order;
use App\Product;
use App\Transaction;
use App\User;
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

   /** @test */
   public function BuyerAdmins_can_see_costs()
   {
       $this->withoutExceptionHandling();
       $this->prepNormalEnv('BuyerAdmin', 'see-costs', 0, 1);
       $user = Auth::user();
       factory('App\Status')->create();
       $this->prepOrder();
       $product = Product::find(1);
       $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
       $this->get('/costs')->assertSeeText($cost->description);
   }

   /**
    * this should be tested in VueJs
    */
    public function form_is_available_to_create_a_cost()
    {

    }

    /** @test */
    public function BuyerAdmins_can_create_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', 'create-costs', 0 , 1  );
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];

        $this->post('/costs', $attributes);
        $this->assertDatabaseHas('costs', $attributes);
    }

   /**
    /** @test
     * for user model
     * one to many relationship
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
     * one to many relationship
     * for user model
     */
    public function each_cost_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order','costable_id' =>$order->id ]);
        $this->assertInstanceOf(User::class, $cost->user);
    }

    /** @test
     * for Order model
     * polymorphic relationship
     */
    public function each_order_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order','costable_id' =>$order->id ]);
        $this->assertInstanceOf(Cost::class, $order->costs->find(1));
    }

    /** @test
     * for order model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_an_order()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order','costable_id' =>$order->id ]);
        $this->assertInstanceOf(Order::class, $cost->costable);
    }

    /** @test
     * for Product model
     * polymorphic relationship
     */
    public function each_product_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Product','costable_id' =>$product->id ]);
        $this->assertInstanceOf(Cost::class, $product->costs->find(1));
    }

    /** @test
     * for product model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_a_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Product','costable_id' =>$product->id ]);
        $this->assertInstanceOf(Product::class, $cost->costable);
    }
    /** @test
     * for Transaction model
     * polymorphic relationship
     */
    public function each_transaction_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Transaction','costable_id' =>$transaction->id ]);
        $this->assertInstanceOf(Cost::class, $transaction->costs->find(1));
    }

    /** @test
     * for transaction model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_a_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Transaction','costable_id' =>$transaction->id ]);
        $this->assertInstanceOf(Transaction::class, $cost->costable);
    }

    /** @test
     * for Kargo model
     * polymorphic relationship
     */
    public function each_kargo_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $kargo = factory('App\Kargo')->create(['user_id' => $user->id]);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Kargo','costable_id' =>$kargo->id ]);
        $this->assertInstanceOf(Cost::class, $kargo->costs->find(1));
    }

    /** @test
     * for Kargo model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-cost', 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $kargo = factory('App\Kargo')->create(['user_id' => $user->id]);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Kargo','costable_id' =>$kargo->id ]);
        $this->assertInstanceOf(Kargo::class, $cost->costable);
    }


}
