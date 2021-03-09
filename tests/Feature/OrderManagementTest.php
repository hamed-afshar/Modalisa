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
use Illuminate\Support\Facades\Storage;
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
     * @test
     *  Users can create orders
     *  users should have create orders permission to be allowed
     */
    public function users_can_create_orders_with_at_least_one_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        //default customer_id for all created orders will be 1, which means it belongs to the retailer.
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status', 2)->create();
        //prepare attributes
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
        $this->post('/orders', $attributes);
        $order = Order::find(1);
        $product = Product::find(1);
        $image_name = $product->images()->where('imagable_id', $product->id)->value('image_name');
        //assert to check order existence in db
        $this->assertDatabaseHas('orders', ['user_id' => $retailer->id, 'customer_id' => $customer->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'order_id' => $order->id]);
        $this->assertDatabaseHas('Images', ['imagable_id' => $product->id, 'imagable_type' => 'App\Product']);
        $this->assertFileExists(public_path('storage' . $image_name));
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
     * users can assign customers for orders
     * users should have create-orders permission to be allowed
     */
    public function assign_customer_to_orders()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        //create an order then assign new customer
        $this->prepOrder(0, 1);
        $newCustomer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        $order = Order::find(1);
        $this->post('/assign-customer/' . $newCustomer->id . '/' . $order->id);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'customer_id' => $newCustomer->id]);
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
        $this->prepOrder(0, 1);
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
        $this->assertFileExists(public_path('storage' . $image_name));
        $this->assertDatabaseHas('images', ['imagable_id' => $product->id, 'imagable_type' => 'App\Product']);
    }

    /**
     * @test
     * users can remove products from order
     * users should have create-orders permission to be allowed
     */
    public function delete_product_from_orders_with_more_than_two_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status', 2)->create();
        $attributesProduct1 = [
            'size' => 'X-Large',
            'color' => 'Black',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/orders', $attributesProduct1);
        $order1 = Order::find(1);
        $attributesProduct2 = [
            'size' => 'Large',
            'color' => 'White',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->post('/add-to-order/' . $order1->id, $attributesProduct2);
        $product2 = Product::find(2);
        $image_name = $product2->images()->where('imagable_id', $product2->id)->value('image_name');
        $this->delete('/delete-product/' . $product2->id);
        //keep the order and delete the product
        $this->assertDatabaseMissing('products', ['id' => $product2->id]);
        $this->assertDatabaseHas('orders', ['id' => $order1->id]);
        //at the same time delete the respective image record and file for the deleted product.
        $this->assertDatabaseMissing('images', ['imagable_id' => $product2->id, 'imagable_type' => 'App\Product']);
        $this->assertFileDoesNotExist(public_path('storage' . $image_name));
    }

    /**
     * @test
     * users can remove products from order
     * users should have create-orders permission to be allowed
     */
    public function delete_product_from_orders_with_one_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status', 2)->create();
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
        $this->post('/orders', $attributes);
        $order = Order::find(1);
        $product = Product::find(1);
        $image_name = $product->images()
            ->where('imagable_id', $product->id)
            ->where('imagable_type', 'App\Product')
            ->value('image_name');
        $this->delete('/delete-product/' . $product->id);
        //Both order and product will be deleted
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        //old respective image file and database record also must be deleted
        $this->assertDatabaseMissing('images', ['imagable_id' => $product->id, 'imagable_type' => 'App\Product']);
        $this->assertFileDoesNotExist(public_path('storage' . $image_name));
    }

    /**
     * @test
     * users can edit products
     * users can only edit products that has not been bought yet
     * users should have create-order permission to be allowed
     */
    public function users_can_edit_products_that_has_not_been_bought_yet()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $this->prepOrder(0, 1);
        $product = Product::find(1);
        //create all possible statuses in db
        $this->prepStatus();
        $newProductAttributes = [
            'size' => 'Medium',
            'color' => 'White',
            'link' => 'www.mango.com',
            'price' => '150',
            'quantity' => '1',
            'country' => 'UK',
            'currency' => 'Pound',
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        $this->patch('/edit-product/' . $product->id, $newProductAttributes);
        $image_name = $product->images()
            ->where('imagable_id', $product->id)
            ->where('imagable_type', 'App\Product')
            ->value('image_name');
        $this->assertDatabaseHas('products', [
            'size' => 'Medium',
            'color' => 'White',
            'link' => 'www.mango.com',
            'price' => '150',
            'quantity' => '1',
            'country' => 'UK',
            'currency' => 'Pound',
        ]);
        //record for this change should be created in the histories table
        $this->assertDatabaseHas('histories', ['product_id' => $product->id, 'status_id' => 10]);
        //assert to check existence of the new uploaded file
        $this->assertFileExists(public_path('storage' . $image_name));
    }

    /**
     * @test
     */
    public function old_image_file_should_be_deleted_after_editing_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        //first create a product with image
        $customer = factory('App\Customer')->create(['user_id' => $retailer->id]);
        factory('App\Status', 2)->create();
        $attributes = [
            'size' => 'X-Large',
            'color' => 'Black',
            'link' => 'www.zara.com',
            'price' => '250',
            'quantity' => '1',
            'country' => 'Turkey',
            'currency' => 'TL',
            'image' => UploadedFile::fake()->create('product1.jpg')
        ];
        //create image file
        Storage::disk('public')->put('/images/product1.jpg', 'Contents');
        $this->post('/orders/', $attributes);
        $order = Order::find(1);
        $product = Product::find(1);
        $oldImageName = $product->images()->where('imagable_id', $product->id)->value('image_name');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'order_id' => $order->id]);
        $this->assertFileExists(public_path('storage' . $oldImageName));
        //edit the product with new attributes
        $this->prepStatus();
        $newAttributes = [
            'size' => 'Medium',
            'color' => 'White',
            'link' => 'www.mango.com',
            'price' => '150',
            'quantity' => '1',
            'country' => 'UK',
            'currency' => 'Pound',
            'image' => UploadedFile::fake()->create('newProduct1.jpg')
        ];
        $this->patch('/edit-product/' . $product->id, $newAttributes);
        //check the new image existence
        $newImageName = $product->images()
            ->where('imagable_id', $product->id)
            ->where('imagable_type', 'App\Product')
            ->value('image_name');
        $this->assertFileExists(public_path('storage' . $newImageName));
        //old image and respective record must be deleted
        $this->assertFileDoesNotExist(public_path('storage'. $oldImageName));
        $this->assertDatabaseMissing('images', [
            'imagable_id'=>$product->id,
            'imagable_type' => 'App\Product',
            'image_name' => $oldImageName
        ]);
    }

    /**
     * @test
     * Super Privilege users can index orders
     */
    public function super_privilege_users_can_index_all_orders_with_relative_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $this->prepNormalEnv('BuyerAdmin', ['see-orders', 'create-orders'], 0, 1);
        $BuyerAdmin = Auth::user();
        //acting as the retailer to create orders
        $this->actingAs($retailer);
        $this->prepOrder(10, 0);
        //acting as the BuyerAdmin to index orders
        $this->actingAs($BuyerAdmin);
        $order = Order::find(1);
        $product = Product::find(5);
        $this->get('/admin-index-orders')
            ->assertSeeText($order->id)
            ->assertSeeText($product->link)
            ->assertSeeText($product->size);
    }

    /**
     * @test
     * Super Privilege users can index a single order
     */
    public function super_privilege_users_can_index_a_single_order_with_relative_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-orders', 'create-orders'], 0, 1);
        $retailer = Auth::user();
        $this->prepNormalEnv('BuyerAdmin', ['see-orders', 'create-orders'], 0, 1);
        $BuyerAdmin = Auth::user();
        //acting as the retailer to create orders
        $this->actingAs($retailer);
        $this->prepOrder(10, 0);
        //acting as the BuyerAdmin to index orders
        $this->actingAs($BuyerAdmin);
        $order = Order::find(1);
        $product = Product::find(5);
        $this->get('/admin-index-orders/' . $order->id)
            ->assertSeeText($order->id)
            ->assertSeeText($product->link)
            ->assertSeeText($product->size);
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
