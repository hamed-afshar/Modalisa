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
use Illuminate\Http\UploadedFile;
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
    public function users_can_create_orders_with_at_least_one_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-Large',
            'color' => 'Black',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes);
        $order = Order::find(1);
        $product = Product::find(1);
        $image_name = $product->images()->where('imagable_id', $product->id)->value('image_name');
        //assert to check order existence in db
        $this->assertDatabaseHas('orders', ['user_id' => $retailer->id, 'customer_id' => $customer->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id,'order_id' => $order->id]);
        $this->assertDatabaseHas('Images', ['imagable_id' => $product->id, 'imagable_type' => 'App\Product']);
        $this->assertFileExists(public_path('storage' . $image_name));
    }

    /** @test */
    public function customer_id_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => '',
            'size' => 'medium',
            'color' => 'Black',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('customer_id');
    }

    /** @test */
    public function size_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => '',
            'color' => 'Black',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('size');
    }

    /** @test */
    public function color_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-large',
            'color' => '',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('color');
    }

    /** @test */
    public function link_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-large',
            'color' => 'white',
            'link' => '',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('link');
    }

    /** @test */
    public function price_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-large',
            'color' => 'white',
            'link' => 'www.zara.com',
            'price' => '',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('price');
    }

    /** @test */
    public function quantity_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-large',
            'color' => 'white',
            'link' => '',
            'price' => '250',
            'quantity' => '',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function country_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-large',
            'color' => 'white',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => '',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('country');
    }

    /** @test */
    public function currency_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-large',
            'color' => 'white',
            'link' => '',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => '',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('currency');
    }

    /** @test */
    public function image_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status')->create();
        //prepare attributes
        $attributes = [
            'customer_id' => $customer->id,
            'size' => 'X-large',
            'color' => 'white',
            'link' => '',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => ''
        ];
        $this->post('/orders', $attributes)->assertSessionHasErrors('image');
    }

    /**
     * @test
     * users can add products to the orders
     * users should have create orders permission to be allowed
     */
    public function addProduct()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $this->prepOrder(0,1);
        $order = Order::find(1);
        $attributes = [
            'size' => 'X-Large',
            'color' => 'Black',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/add-to-order/' . $order->id, $attributes);
        $product = Product::find(2);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'order_id' => $order->id]);
        $image_name = $product->images()->where('imagable_id', $product->id)->value('image_name');
        $this->assertFileExists(public_path('storage' . $image_name ));
    }

    /**
     * @test
     * users can delete a products from order
     * users should have create order permission to be allowed
     * if the parent order just have one product then the order will be deleted as well.
     */
    public function deleteProduct()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $this->prepOrder(0,1);
        $order = Order::find(1);
        $attributes = [
            'size' => 'X-Large',
            'color' => 'Black',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/add-to-order/' . $order->id, $attributes);
        $product = Product::find(2);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'order_id' => $order->id]);
        $image_name = $product->images()->where('imagable_id', $product->id)->value('image_name');
        $this->assertFileExists(public_path('storage' . $image_name ));
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
