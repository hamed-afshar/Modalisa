<?php

namespace Tests\Feature;

use App\Customer;
use App\Kargo;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProjectTests extends TestCase
{

    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function retailer_can_create_order()
    {
        $this->withoutExceptionHandling();
        $this->prepRetailerEnv('Retailer', 'create-order', 0, 1);
        $attributes = [
            'id' => $this->faker->numberBetween($min = 3000, $max = 4000),
            'user_id' => $user->id,
            'country' => 'Turkey'
        ];
        $this->post('/orders', $attributes)->assertRedirect('/orders');
        $this->assertDatabaseHas('Orders', $attributes);
        $this->get('/orders')->assertSee($attributes['id']);
    }

    /** @test */
    public function an_order_requires_orderID()
    {
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Order')->raw(['id' => '']);
        $this->post('/orders', $attributes)->assertSessionHasErrors('id');
    }

    /** @test */
    public function an_order_requires_userID()
    {
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Order')->raw(['user_id' => '']);
        $this->post('/orders', $attributes)->assertSessionHasErrors('user_id');
    }

    /** @test */
    public function an_order_requires_country()
    {
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Order')->raw(['country' => '']);
        $this->post('/orders', $attributes)->assertSessionHasErrors('country');
    }

    /** @test */
    public function a_user_can_view_their_order()
    {
        $this->be(factory('App\User')->create());
        $order = factory('App\Order')->create(['user_id' => auth()->id()]);
        $this->get($order->path())
            ->assertSee($order->id);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_orders_of_others()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $order = factory('App\Order')->create();
        $this->get($order->path())->assertRedirect('/access-denied');
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $order = factory('App\Order')->create();
        $this->assertInstanceOf('App\User', $order->owner);
    }

    /** @test */
    public function other_users_can_not_access_order_management_system()
    {
        $user = factory('App\User')->create(['access_level' => 'Accountant']);
        $this->actingAs($user);
        // other users can not make order
        $this->post('/orders')->assertRedirect('/access-denied');
    }

    /** @test */
    public function guest_can_not_access_order_management_system()
    {
        $attributes = factory('App\Order')->raw();
        $order = factory('App\Order')->create();
        //guests can not create user
        $this->post('/orders', $attributes)->assertRedirect('login');
        //guests can not view projects
        $this->get('/orders')->assertRedirect('login');
        //guests can not view a single order
        $this->get($order->path())->assertRedirect('login');
    }

    /** @test */
    public function orders_can_not_be_deleted_from_system()
    {
        $this->delete('/orders')->assertRedirect('access-denied');
    }

    /** @test
     * one to many relationship
     */

    public function each_user_has_many_orders()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $this->prepOrder();
        $this->assertInstanceOf(Order::class, Auth::user()->orders->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_order_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders', 'see-orders'], 0, 1);
        $this->prepOrder();
        $order = Order::find(1);
        $this->assertInstanceOf(User::class, $order->user);
    }

    /** @test
     * one to many relationship
     */
    public function each_customer_has_many_orders()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $this->prepOrder();
        $customer = Customer::find(1);
        $this->assertInstanceOf(Order::class, $customer->orders->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_order_belongs_to_a_customer()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $this->prepOrder();
        $order = Order::find(1);
        $this->assertInstanceOf(Customer::class, $order->customer);
    }


    /** @test
     * one to many through relationship
     */
    public function each_user_has_many_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['make-order', 'see-orders'], 0, 1);
        $this->prepOrder();
        $this->assertInstanceOf(Product::class, Auth::user()->products->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_karo_has_many_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $kargo = Kargo::find(1);
        $this->assertInstanceOf(Product::class, $kargo->products->find(1));
    }

    /** @test
     * one to many relation ship
     */
    public function each_product_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $this->assertInstanceOf(Kargo::class, $product->kargo);
    }


}
