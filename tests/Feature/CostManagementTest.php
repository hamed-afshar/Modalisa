<?php

namespace Tests\Feature;

use App\Cost;
use App\Image;
use App\Kargo;
use App\Order;
use App\Permission;
use App\Product;
use App\Traits\ImageTrait;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CostManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test
     * only BuyerAdmins have create-costs permission for creating a cost record
     */

    public function only_buyer_admin_can_create_update_and_delete_costs()
    {
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];

        $this->post('/costs', $attributes)->assertForbidden();
    }

    /** @test
     * first BuyerAdmin should create a cost for a specific user
     * then that user will be able to see all costs created for him
     */
    public function retailers_can_see_all_costs()
    {
        $this->withoutExceptionHandling();
        //create a BuyerAdmin user with permissions to see and create costs
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        //create a retailer user with permission only to see costs
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->prepOrder();
        $product = Product::find(1);
        // acting as BuyerAdmin to create a cost for retailer.
        $this->actingAs($BuyerAdmin);
        $cost = factory('App\Cost')->create(['user_id' => $retailer->id, 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        // acting as a retailer to  check created cost existence
        $this->actingAs($retailer);
        $this->get('/costs')->assertSeeText($cost->description);
    }

    /** @test
     * retailer can see all costs related to a specific model.
     * first BuyerAdmin should create a cost for a specific user and model
     * then that user will be able to see created cost for him related to that model
     * here we use product model for testing. Using any other model is also possible
     */
    public function retailer_can_see_all_costs_related_to_a_specific_model()
    {
        $this->withoutExceptionHandling();
        //create a BuyerAdmin user with permissions to see and create costs
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        //create a retailer user with permission only to see costs
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->prepOrder();
        $product = Product::find(1);
        // acting as BuyerAdmin to create a cost for retailer.
        $this->actingAs($BuyerAdmin);
        $cost = factory('App\Cost')->create(['user_id' => $retailer->id, 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        // acting as a retailer to  check created cost existence
        $this->actingAs($retailer);
        $model = 'App\Product';
        $this->get('/costs-model/' . $model)->assertSeeText($cost->description);
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_create_a_cost()
    {

    }

    /** @test
     * BuyerAdmin can create cost for a specific user
     */
    public function BuyerAdmins_can_create_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for retailer
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes);
        $this->assertDatabaseHas('costs', ['amount' => 1000, 'description' => 'cost for product', 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
    }

    /** @test */
    public function user_is_required()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => '',
            'amount' => '',
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for retailer
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes)->assertSessionHasErrors('user');
    }

    /** @test */
    public function amount_is_required()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => '',
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for retailer
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes)->assertSessionHasErrors('amount');
    }

    /** @test */
    public function description_is_required()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => '',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for retailer
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function costable_type_is_required()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => '',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for retailer
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes)->assertSessionHasErrors('costable_type');
    }

    /** @test */
    public function costable_id_is_required()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => ''
        ];
        // acting as a BuyerAdmin to create cost for retailer
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes)->assertSessionHasErrors('costable_id');
    }

    /** @test
     * test image upload functionality on cost creation time separately here
     */
    public function image_can_be_uploaded_on_cost_creation()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id,
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        // acting as BuyerAdmin to create a cost record
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes);
        $cost = Cost::find(1);
        $image = $cost->images()->find($cost->id);
        $image_name = $image->image_name;
        // Assert file exist on server
        $this->assertFileExists(public_path('storage' . $image_name));
        // Assert database has image which has a imagable_id for created transaction
        $this->assertDatabaseHas('images', ['imagable_id' => $cost->id]);
    }

    /** @test
     * retailer can see a single cost
     * first BuyerAdmin must create the cost, then retailer will be able to see that single cost record
     */
    public function retailers_can_see_a_single_cost()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        //first assert to see that cost created successfully by BuyerAdmin
        $this->actingAs($BuyerAdmin);
        $this->post('/costs', $attributes);
        $this->assertDatabaseHas('costs', ['amount' => 1000, 'description' => 'cost for product', 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        // acting as a retailer to check single record for the created cost
        $this->actingAs($retailer);
        $cost = Cost::find(1);
        $this->get($cost->path())->assertSeeText($cost->description);
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_a_cost()
    {

    }

    /** @test
     * BuyerAdmin first create a record for a cost, then update it.
     */
    public function only_BuyerAdmin_can_update_a_cost()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);

        // create a cost record for the product
        factory('App\Cost')->create([
            'user_id' => $retailer->id,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id,
        ]);
        $cost = Cost::find(1);
        // create an image record for the created cost
        factory('App\Image')->create([
           'user_id' => $retailer->id,
           'imagable_type' => 'App\Cost',
           'imagable_id' => $cost->id,
           'image_name' => '/images/cost1.jpg'
        ]);
        // create image file for cost record in the images folder
        Storage::disk('public')->put('/images/cost1.jpg', 'Contents');
        //get uploaded image name for created cost record
        $cost = Cost::find(1);
        $oldImageName = $cost->images()->where('imagable_id', $cost->id)->value('image_name');
        $newAttributesWithoutImage = [
            'user' => $retailer,
            'amount' => 2000,
            'description' => 'new cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id,
        ];
        $newAttributesWithImage = [
            'user' => $retailer,
            'amount' => 2000,
            'description' => 'new cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id,
            'image' => UploadedFile::fake()->create('pic.jpg')
        ];
        // acting as BuyerAdmin to have permission for update costs records
        $this->actingAs($BuyerAdmin);
        // update the cost record without new image
        $this->patch('/costs/' . $cost->id, $newAttributesWithoutImage);
        $this->assertDatabaseHas('costs', ['amount' => 2000, 'description' => 'new cost for product', 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        // old image should remain intact
        $this->assertFileExists(public_path('storage' . $oldImageName));
        // update cost record with new image
        $this->patch('/costs/' . $cost->id, $newAttributesWithImage);
        $this->assertDatabaseHas('costs', ['amount' => 2000, 'description' => 'new cost for product', 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        $this->assertFileNotExists(public_path('storage' . $oldImageName));
    }


    /** @test
     * for user model
     * one to many relationship
     */
    public function each_user_can_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order', 'costable_id' => $order->id]);
        $this->assertInstanceOf(Cost::class, $user->costs->find(1));
    }

    /** @test
     * one to many relationship
     * for user model
     */
    public function each_cost_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order', 'costable_id' => $order->id]);
        $this->assertInstanceOf(User::class, $cost->user);
    }

    /** @test
     * for Order model
     * polymorphic relationship
     */
    public function each_order_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order', 'costable_id' => $order->id]);
        $this->assertInstanceOf(Cost::class, $order->costs->find(1));
    }

    /** @test
     * for order model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_an_order()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Order', 'costable_id' => $order->id]);
        $this->assertInstanceOf(Order::class, $cost->costable);
    }

    /** @test
     * for Product model
     * polymorphic relationship
     */
    public function each_product_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        $this->assertInstanceOf(Cost::class, $product->costs->find(1));
    }

    /** @test
     * for product model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_a_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        $this->assertInstanceOf(Product::class, $cost->costable);
    }

    /** @test
     * for Transaction model
     * polymorphic relationship
     */
    public function each_transaction_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Transaction', 'costable_id' => $transaction->id]);
        $this->assertInstanceOf(Cost::class, $transaction->costs->find(1));
    }

    /** @test
     * for transaction model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_a_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Transaction', 'costable_id' => $transaction->id]);
        $this->assertInstanceOf(Transaction::class, $cost->costable);
    }

    /** @test
     * for Kargo model
     * polymorphic relationship
     */
    public function each_kargo_may_have_many_costs()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $kargo = factory('App\Kargo')->create(['user_id' => $user->id]);
        factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Kargo', 'costable_id' => $kargo->id]);
        $this->assertInstanceOf(Cost::class, $kargo->costs->find(1));
    }

    /** @test
     * for Kargo model
     * polymorphic relationship
     */
    public function each_cost_may_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-cost'], 0, 1);
        $user = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder();
        $kargo = factory('App\Kargo')->create(['user_id' => $user->id]);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Kargo', 'costable_id' => $kargo->id]);
        $this->assertInstanceOf(Kargo::class, $cost->costable);
    }


}
