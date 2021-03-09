<?php

namespace Tests\Feature;

use App\Cost;
use App\Image;
use App\Kargo;
use App\Order;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageManagementTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    /** user is not supposed to index all of the uploaded pictures.
     * it only needs to see pictures related to any model specifically
     * so this test is not neccessary
     */
    public function user_can_see_its_own_images_related_to_a_model()
    {

    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_upload_an_image()
    {

    }

    /** @test
     * users should have create-images permission to be allowed
     */
    public function user_can_upload_and_store_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'see-costs'], 0, 1);
        $this->prepOrder(1,0);
        $order = Order::find(1);
        $attributes = [
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
            'image' => UploadedFile::fake()->create('image.jpg'),
        ];
        $this->post('/images', $attributes);
        $image_name = Image::find(1)->image_name;
        $this->assertDatabaseHas('images', ['imagable_type' => 'App\Order', 'imagable_id' => $order->id]);
        $this->assertFileExists(public_path('storage') . $image_name);
    }

    /** @test */
    public function image_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-images', 'see-costs'], 0, 1);
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $attributes = [
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
            'image' => '',
        ];
        $this->post('/images', $attributes)->assertSessionHasErrors('image');
    }

    /** @test */
    public function only_valid_extensions_for_images_are_acceptable()
    {
        $this->prepNormalEnv('retailer', ['create-images', 'see-costs'], 0, 1);
        factory('App\Status')->create();
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $attributes = [
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
            'image' => UploadedFile::fake()->create('image.pnghj'),
        ];
        $this->post('/images', $attributes)->assertSessionHasErrors('image');
    }

    /** @test */
    public function imagable_type_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-images', 'see-costs'], 0, 1);
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $attributes = [
            'user_id' => Auth::user()->id,
            'imagable_type' => '',
            'imagable_id' => $order->id,
            'image' => UploadedFile::fake()->create('image.png'),
        ];
        $this->post('/images', $attributes)->assertSessionHasErrors('imagable_type');
    }

    /** @test */
    public function imagable_id_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-images', 'see-costs'], 0, 1);
        $this->prepOrder(1, 0);
        $attributes = [
            'user_id' => Auth::user()->id,
            'imagable_type' => 'App\Order',
            'imagable_id' => '',
            'image' => UploadedFile::fake()->create('image.png'),
        ];
        $this->post('/images', $attributes)->assertSessionHasErrors('imagable_id');
    }

    /** @test
     * users should have see-images permission to be allowed
     * users can only see their own records
     */
    public function user_can_see_a_single_photo()
    {
        $this->prepNormalEnv('retailer1', ['create-images', 'see-images'], 0, 1);
        $retailer1 = Auth::user();
        $this->prepOrder(1,0);
        $order = Order::find(1);
        $image = factory('App\Image')->create([
            'user_id' => $retailer1->id,
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
            'image_name' => 'test.jpg'
        ]);
        $this->get($image->path())->assertSeeText($image->image_name);
        // users can only see their own images
        $this->prepNormalEnv('retailer2', ['create-images', 'see-images'], 0, 1);
        $this->get($image->path())->assertForbidden();
    }

    /**
     * This should be tested in VueJs
     */
    public function form_is_available_to_update_a_photo()
    {

    }

    /** @test
     * users should have create-images to be allowed
     * users can only update their own records
     * old image file should be deleted after uploading a new one
     */
    public function user_can_update_a_photo()
    {
        $this->prepNormalEnv('retailer1', ['create-images', 'see-images'], 0, 1);
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $attributes = [
            'user_id' => Auth::user()->id,
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
            'image' => UploadedFile::fake()->create('image1.jpg'),
        ];
        $this->post('/images', $attributes);
        $oldImage = Image::find(1);
        $oldImageName = $oldImage->image_name;
        $newImage = UploadedFile::fake()->create('image2.jpg');
        $newAttributes = [
            'user_id' => Auth::user()->id,
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
            'image' => $newImage
        ];
        $this->patch($oldImage->path(), $newAttributes);
        $newImageName = $order->images()->where(['imagable_id' => $order->id, 'imagable_type' => 'App\Order'])->value('image_name');
        //assert image updated in db
        $this->assertDatabaseHas('images', ['user_id' => Auth::user()->id, 'imagable_type' => 'App\Order' ,'image_name' => $newImageName]);
        //assert new image exist on server
        $this->assertFileExists(public_path('storage') . $newImageName);
        //assert old image deletes from server
        $this->assertFileDoesNotExist(public_path('storage' . $oldImageName));
        // users can only update their own records
        $this->prepNormalEnv('retailer2', ['create-images', 'see-images'], 0, 1);
        $this->patch($oldImage->path(), $newAttributes)->assertForbidden();

    }

    /** @test
     * users should have delete-images permission to be allowed
     * users can only delete their own images
     */
    public function user_can_delete_a_photo()
    {
        $this->prepNormalEnv('retailer1', ['create-images', 'delete-images'], 0, 1);
        $retailer1 = Auth::user();
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        factory('App\Image')->create([
            'user_id' => Auth::user()->id,
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
            'image_name' => 'images/image1.jpg',
        ]);
        Storage::disk('public')->put('/images/image1.jpg', 'contents');
        // first create an image
        $image = Image::find(1);
        $imageName = $image->image_name;
        //users can only delete their own records
        $this->prepNormalEnv('retailer2', ['create-images', 'delete-images'], 0, 1);
        $this->delete($image->path())->assertForbidden();
        //delete the image and respective record
        $this->actingAs($retailer1);
        $this->delete($image->path());
        $this->assertDatabaseMissing('images', ['id' => $image->id, 'imagable_type' => 'App\Order', 'imagable_id' => $order->id]);
        $this->assertFileDoesNotExist(public_path('storage' . $imageName));
    }


    /** all relationship related to Image model should be tested
     * Model that has normal relationship is: User
     * Models that have a polymorphic relationship with Image model are:
     * Transaction, Cost, Order, Product, Kargo
     */

    /** @test
     * for User model
     * one to many relationship
     */
    public function each_user_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create([
            'user_id' => $user->id,
        ]);
        factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'transaction1.jpg',
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id
        ]);
        $this->assertInstanceOf(Image::class, $user->images->find(1));
    }

    /** @test
     * for User model
     * one to many relationship
     */
    public function each_image_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create([
            'user_id' => $user->id,
        ]);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'transaction1.jpg',
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id
        ]);
        $this->assertInstanceOf(User::class, $image->user);
    }

    /** @test
     * for Transaction model
     * polymorphic one to many relationship
     */
    public function transactions_may_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create([
            'user_id' => $user->id,
        ]);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'transaction1.jpg',
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id
        ]);
        $this->assertInstanceOf(Image::class, $transaction->images->find(1));
    }

    /** @test
     * for Transaction model
     * polymorphic one to many relationship
     */
    public function each_image_may_belongs_to_a_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create([
            'user_id' => $user->id
        ]);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'transaction1.jpg',
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id
        ]);
        $this->assertInstanceOf(Transaction::class, $image->imagable);
    }

    /** @test
     * for Cost model
     * polymorphic one to many relationship
     */
    public function cost_may_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder(1,0);
        $order = Order::find(1);
        $cost = factory('App\Cost')->create([
            'user_id' => $user->id,
            'costable_type' => 'App\Order',
            'costable_id' => $order->id
        ]);
        factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'cost1.jpg',
            'imagable_type' => 'App\Cost',
            'imagable_id' => $cost->id,
        ]);
        $this->assertInstanceOf(Image::class, $cost->images->find(1));
    }

    /** @test
     * for Cost model
     * polymorphic one to many relationship
     */
    public function each_image_may_belongs_to_a_cost()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $cost = factory('App\Cost')->create([
            'user_id' => $user->id,
            'costable_type' => 'App\Order',
            'costable_id' => $order->id
        ]);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'cost1.jpg',
            'imagable_type' => 'App\Cost',
            'imagable_id' => $cost->id,
        ]);
        $this->assertInstanceOf(Cost::class, $image->imagable);
    }

    /** @test
     * for Order model
     * polymorphic one to many relationship
     */
    public function order_may_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'order1.jpg',
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
        ]);
        $this->assertInstanceOf(Image::class, $order->images->find(1));
    }

    /** @test
     * for Order model
     * polymorphic one to many relationship
     */
    public function each_image_may_belongs_to_an_order()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'order1.jpg',
            'imagable_type' => 'App\Order',
            'imagable_id' => $order->id,
        ]);
        $this->assertInstanceOf(Order::class, $image->imagable);
    }

    /** @test
     *  for Product model
     *  polymorphic one to many relationship
     */
    public function product_may_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $product = factory('App\Product')->create([
            'order_id' => $order->id,
        ]);
        factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'product1.jpg',
            'imagable_type' => 'App\Product',
            'imagable_id' => $product->id,
        ]);
        $this->assertInstanceOf(Image::class, $product->images->find(1));
    }

    /** @test
     *  for Product model
     *  polymorphic one to many relationship
     */
    public function each_image_may_belong_to_a_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $product = factory('App\Product')->create([
            'order_id' => $order->id,
        ]);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'product1.jpg',
            'imagable_type' => 'App\Product',
            'imagable_id' => $product->id,
        ]);
        $this->assertInstanceOf(Product::class, $image->imagable);
    }

    /** @test
     *  for Kargo model
     *  polymorphic one to many relationship
     */
    public function kargo_may_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        $kargo = factory('App\Kargo')->create([
            'user_id' => $user->id,
        ]);
        factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'kargo1.jpg',
            'imagable_type' => 'App\Kargo',
            'imagable_id' => $kargo->id,
        ]);
        $this->assertInstanceOf(Image::class, $kargo->images->find(1));
    }

    /** @test
     *  for Kargo model
     *  polymorphic one to many relationship
     */
    public function each_image_may_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-images', 'delete-images'], 0, 1);
        $user = Auth::user();
        $kargo = factory('App\Kargo')->create([
            'user_id' => $user->id,
        ]);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => 'kargo1.jpg',
            'imagable_type' => 'App\Kargo',
            'imagable_id' => $kargo->id,
        ]);
        $this->assertInstanceOf(Kargo::class, $image->imagable);
    }
}
