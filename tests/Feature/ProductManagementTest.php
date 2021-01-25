<?php

namespace Tests\Feature;

use App\Order;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function each_order_has_many_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders'], 0, 1);
        $this->prepOrder();
        $order = Order::find(1);
        $this->assertInstanceOf(Product::class, $order->products->find(1));
    }

    /** @test */
    public function each_product_belongs_to_an_order()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders'], 0, 1);
        $this->prepOrder();
        $product = Product::find(1);
        $this->assertInstanceOf(Order::class, $product->order);
    }

    /** @test */
    public function each_user_has_many_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders'], 0, 1);
        $user = Auth::user();
        $this->prepOrder();
        $this->assertInstanceOf(Product::class, $user->products->find(1));
    }

    /**
     * as this function is not available in laravel,we have implemented it in products model
     */
    public function each_product_belongs_to_a_user()
    {

    }

}
