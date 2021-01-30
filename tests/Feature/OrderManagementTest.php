<?php

namespace Tests\Feature;

use App\Customer;
use App\Kargo;
use App\Order;
use App\Product;
use App\Status;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{

    use WithFaker,
        RefreshDatabase;


    /**
     * @test
     * users can see their orders with related products
     * users should have see-orders permission to be allowed
     */
    public function users_can_see_all_orders_with_related_products_and_customer()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $this->prepOrder(3, 0);
        $product = Product::find(3);
        $customer = Customer::find(1);
        $this->get('/orders')->assertSeeText($product->link)->assertSeeText($customer->name);
    }

    /**
     * this should be tested in VueJS
     */
    public function fom_is_available_to_create_an_order()
    {

    }

    /**
     *  @test
     *  Users can create orders
     *  users should have create orders permission to be allowed
     */
    public function users_can_create_orders()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        $status = factory('App\Status')->create();
        //create numbers of products
        $productList = array();
        for ($i = 0; $i <= 10; $i++) {
            $product = factory('App\Product')->raw();
            $productList[] = $product;
        }
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'productList' => $productList
        ];
        $this->post('/orders', $attributes);
        //assert to check order existence in db
        $this->assertDatabaseHas('orders', ['user_id' => $retailer->id, 'customer_id' => $customer->id]);
        //assert to see the existence of the products
        for ($i = 0; $i <= 10; $i++) {
            $product = Product::find(1);
            $this->assertDatabaseHas('products', ['id' => $product->id]);
        }
    }

    /** @test */
    public function customer_id_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        $status = factory('App\Status')->create();
        //create numbers of products
        $productList = array();
        for ($i = 0; $i <= 10; $i++) {
            $product = factory('App\Product')->raw();
            $productList[] = $product;
        }
        //prepare attributes
        $attributes = [
            'customer_id' => '',
            'productList' => $productList
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('customer_id');
    }

    /** @test */
    public function productList_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        $status = factory('App\Status')->create();
        //create numbers of products
        $productList = array();
        for ($i = 0; $i <= 10; $i++) {
            $product = factory('App\Product')->raw();
            $productList[] = $product;
        }
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'productList' => ''
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('productList');
    }


    /**
     * @test
     * one to many relationship
     */
    public function each_user_has_many_orders()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders'], 0, 1);
        $this->prepOrder(1, 0);
        $this->assertInstanceOf(Order::class, Auth::user()->orders->find(1));
    }

    /**
     * @test
     * one to many relationship
     */
    public function each_order_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders', 'see-orders'], 0, 1);
        $this->prepOrder(0, 1);
        $order = Order::find(1);
        $this->assertInstanceOf(User::class, $order->user);
    }

    /**
     * @test
     * one to many relationship
     */
    public function each_customer_has_many_orders()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders', 'see-orders'], 0, 1);
        $this->prepOrder(0, 1);
        $customer = Customer::find(1);
        $this->assertInstanceOf(Order::class, $customer->orders->find(1));
    }

    /**
     * @test
     * one to many relationship
     */
    public function each_order_belongs_to_a_customer()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders', 'see-orders'], 0, 1);
        $this->prepOrder(0, 1);
        $order = Order::find(1);
        $this->assertInstanceOf(Customer::class, $order->customer);
    }

}
