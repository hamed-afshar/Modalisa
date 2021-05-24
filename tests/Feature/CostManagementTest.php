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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CostManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test
     * first BuyerAdmin should create a cost for a specific retailer
     * then that retailer will be able to see all costs created for him
     * retailer are not able to see created costs for other retailers.
     */
    public function retailers_only_can_see_all_costs_created_just_for_them()
    {
        $this->withoutExceptionHandling();
        //create a BuyerAdmin user with permissions to see and create costs
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->actingAs($BuyerAdmin, 'api');
        //create a retailer with just see-costs permission
        $this->prepNormalEnv('retailer1', ['see-costs'], 0, 1);
        $retailer1 = Auth::user();
        $this->actingAs($retailer1, 'api');
        $this->prepOrder(1,0);
        $product = Product::find(1);
        //acting as the BuyerAdmin to create a cost for the retailer.
        $this->actingAs($BuyerAdmin, 'api');
        $cost = factory('App\Cost')->create([
            'user_id' => $retailer1->id,
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ]);
        //acting as the retailer to check created costs existence
        $this->actingAs($retailer1, 'api');
        $this->get('api/costs')->assertSeeText($cost->description);
        //retailers are only able to see costs created just for them
        $this->prepNormalEnv('retailer2', ['see-costs'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->get('api/costs')->assertDontSeeText($cost->description);
    }

    /** @test
     * retailer can see all costs related to a specific model
     * first BuyerAdmin should create a cost for a specific user and model
     * then that user will be able to see created cost for him related to that record
     * here we use product model for testing, using any other model is also possible
     */
    public function retailer_can_see_all_costs_related_to_a_specific_model()
    {
        $this->withoutExceptionHandling();
        //create BuyerAdmin with permissions to see and create costs
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->actingAs($BuyerAdmin, 'api');
        //create retailer with permission only to see costs
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        $retailer1 = Auth::user();
        $this->actingAs($retailer1, 'api');
        $this->prepOrder(1,0);
        $product = Product::find(1);
        // acting as BuyerAdmin to create a costs for retailer.
        $this->actingAs($BuyerAdmin, 'api');
        $cost1 = factory('App\Cost')->create([
            'user_id' => $retailer1->id,
            'costable_type' => 'App\Product',
            'costable_id' => $product->id,
            'description' => 'cost1'
        ]);
        $cost2 = factory('App\Cost')->create([
            'user_id' => $retailer1->id,
            'costable_type' => 'App\Product',
            'costable_id' => $product->id,
            'description' => 'cost2'
        ]);
        // acting as a retailer to check created cost existence for the product
        $this->actingAs($retailer1, 'api');
        $model = 'App\Product';
        $id = $product->id;
        $this->get('api/costs-model/' . $id . '/' . $model)->assertSeeText($cost1->description);
        $this->get('api/costs-model/' . $id . '/' . $model)->assertSeeText($cost2->description);
        // all retailers are only able to see their own records
        $this->prepNormalEnv('retailer2', ['see-costs'], 0 , 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->get('api/costs-model/' . $id . '/' . $model)->assertdontSeeText($cost1->description);
        $this->get('api/costs-model/' . $id . '/' . $model)->assertdontSeeText($cost2->description);
    }

    /** @test
     * All super privilege users are able to see all costs created for a specific user
     */
    public function super_privilege_users_can_see_all_costs_related_to_any_user()
    {
        //create a BuyerAdmin user with permissions to see and create costs
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->actingAs($BuyerAdmin, 'api');
        //create a retailer with just see-costs permission
        $this->prepNormalEnv('retailer1', ['see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->prepOrder(1,0);
        $product = Product::find(1);
        //acting as the BuyerAdmin to create a cost for retailer.
        $this->actingAs($BuyerAdmin, 'api');
        $cost = factory('App\Cost')->create([
            'user_id' => $retailer->id,
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ]);
        //assert to see the cost's description created for the retailer
        $this->get('api/admin-index-costs/' . $retailer->id)->assertSeeText($cost->description);
        //other users are not allowed to index costs for a specific user
        $this->actingAs($retailer,'api');
        $this->get('api/admin-index-costs/' . $retailer->id)->assertForbidden();
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_create_a_cost()
    {

    }

    /** @test
     * SuperPrivilege can create cost for a specific user
     * SuperPrivilege must have create-costs permission to be allowed
     */
    public function SuperPrivilege_users_can_create_costs()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->actingAs($BuyerAdmin, 'api');
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->prepOrder(1, 0 );
        $product = Product::find(1);
        $attributes = [
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for retailer
        $this->actingAs($BuyerAdmin, 'api');
        $this->post('api/admin-create-cost/' . $retailer->id, $attributes);
        $this->assertDatabaseHas('costs', ['amount' => 1000, 'description' => 'cost for product', 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        // other users are not allowed to create costs
        $this->actingAs($retailer, 'api');
        $this->post('api/costs', $attributes)->assertForbidden();
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
        $this->prepOrder(1,0);
        $product = Product::find(1);
        $attributes = [
            'amount' => '',
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for the retailer
        $this->actingAs($BuyerAdmin, 'api');
        $this->post('api/admin-create-cost/' . $retailer->id, $attributes)->assertSessionHasErrors('amount');
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
        $this->prepOrder(1,0);
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => '',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for the retailer
        $this->actingAs($BuyerAdmin, 'api');
        $this->post('api/admin-create-cost/' . $retailer->id, $attributes)->assertSessionHasErrors('description');
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
        $this->prepOrder(1,0);
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => '',
            'costable_id' => $product->id
        ];
        // acting as a BuyerAdmin to create cost for the retailer
        $this->actingAs($BuyerAdmin, 'api');
        $this->post('api/admin-create-cost/' . $retailer->id, $attributes)->assertSessionHasErrors('costable_type');
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
        $this->prepOrder(1,0);
        Product::find(1);
        $attributes = [
            'user' => $retailer,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => ''
        ];
        // acting as a BuyerAdmin to create cost for the retailer
        $this->actingAs($BuyerAdmin, 'api');
        $this->post('api/admin-create-cost/' . $retailer->id, $attributes)->assertSessionHasErrors('costable_id');
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
        $this->prepOrder(1,0);
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
        $this->actingAs($BuyerAdmin, 'api');
        $this->post('api/admin-create-cost/' . $retailer->id, $attributes);
        $cost = Cost::find(1);
        $image = $cost->images()->find($cost->id);
        $image_name = $image->image_name;
        // Assert file existence on the server
        $this->assertFileExists(public_path('storage' . $image_name));
        // Assert database has image record for the created cost
        $this->assertDatabaseHas('images', ['user_id' => $retailer->id,'imagable_type' => 'App\Cost','imagable_id' => $cost->id]);
    }

    /** @test
     * retailer can see a single cost
     * first BuyerAdmin must create the cost, then retailer will be able to see that single cost record
     */
    public function retailers_can_see_a_single_cost()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        $retailer1 = Auth::user();
        $this->prepOrder(1,0);
        $product = Product::find(1);
        $attributes = [
            'user' => $retailer1,
            'amount' => 1000,
            'description' => 'cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ];
        //first assert to see that cost created successfully by BuyerAdmin
        $this->actingAs($BuyerAdmin, 'api');
        $this->post('api/admin-create-cost/' . $retailer1->id, $attributes);
        $this->assertDatabaseHas('costs', ['amount' => 1000, 'description' => 'cost for product', 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        // acting as a retailer to check single record for the created cost
        $this->actingAs($retailer1, 'api');
        $cost = Cost::find(1);
        $this->get($cost->path())->assertSeeText($cost->description);
        // retailers are not allowed to see other retailer's costs
        $this->prepNormalEnv('retailer2', ['see-costs'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->get($cost->path())->assertForbidden();
    }

    /** @test
     * All super privilege users are able to see a single cost for a specific user
     */
    public function super_privilege_users_can_see_a_single_cost_for_a_specific_user()
    {
        //create a BuyerAdmin user with permissions to see and create costs
        $this->prepNormalEnv('BuyerAdmin', ['create-costs', 'see-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        //create a retailer with just see-costs permission
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->prepOrder(1,0);
        $product = Product::find(1);
        //acting as the BuyerAdmin to create a cost for retailer.
        $this->actingAs($BuyerAdmin);
        factory('App\Cost')->create([
            'user_id' => $retailer->id,
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ]);
        $cost = Cost::find(1);
        //assert to see the cost's description created for the retailer
        $this->get('/admin-index-single-cost/' . $retailer->id . '/' . $cost->id)->assertSeeText($cost->description);
        //other users are not allowed to see costs for a specific user
        $this->actingAs($retailer);
        $this->get('/admin-index-single-cost/' . $retailer->id . '/' . $cost->id)->assertForbidden();
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
        $this->prepNormalEnv('BuyerAdmin', ['create-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        $this->prepOrder(1,0);
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
        $this->patch('/admin-update-cost/' . $cost->id, $newAttributesWithoutImage);
        $this->assertDatabaseHas('costs', [
            'amount' => 2000,
            'description' => 'new cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ]);
        // old image should remain intact
        $this->assertFileExists(public_path('storage' . $oldImageName));
        // update cost record with new image
        $this->patch('/admin-update-cost/' . $cost->id, $newAttributesWithImage);
        $this->assertDatabaseHas('costs', [
            'amount' => 2000,
            'description' => 'new cost for product',
            'costable_type' => 'App\Product',
            'costable_id' => $product->id
        ]);
        //old image should be deleted and new image should be uploaded
        $this->assertFileDoesNotExist(public_path('storage' . $oldImageName));
        $newImageName = $cost->images()->where('imagable_id', $cost->id)->value('image_name');
        $this->assertFileExists(public_path('storage' . $newImageName));
        // only BuyerAdmin is allowed to update cost records
        $this->actingAs($retailer);
        $this->patch('/admin-update-cost/' . $cost->id, $newAttributesWithImage)->assertForbidden();
    }

    /** @test
     * BuyerAdmin first create a cost for a product.
     * image record and image file also must be deleted
     */
    public function only_BuyerAdmin_can_delete_costs()
    {
        $this->prepNormalEnv('BuyerAdmin', ['delete-costs'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-costs'], 0, 1);
        // cost will be created for this user
        $retailer = Auth::user();
        $this->prepOrder(1,0);
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
        // create two image record for the created cost
        $image1 = factory('App\Image')->create([
            'user_id' => $retailer->id,
            'imagable_type' => 'App\Cost',
            'imagable_id' => $cost->id,
            'image_name' => '/images/cost1.jpg'
        ]);
        $image2 = factory('App\Image')->create([
            'user_id' => $retailer->id,
            'imagable_type' => 'App\Cost',
            'imagable_id' => $cost->id,
            'image_name' => '/images/cost2.jpg'
        ]);
        // create image files for cost record in the images folder
        Storage::disk('public')->put('/images/cost1.jpg', 'Contents');
        Storage::disk('public')->put('/images/cost2.jpg', 'Contents');
        $imageNameArray = $cost->images()->where('imagable_id', $cost->id)->pluck('image_name');
        //other users are not allowed to delete costs
        $this->actingAs($retailer);
        $this->delete('/admin-delete-cost/' . $cost->id)->assertForbidden();
        //acting as BuyerAdmin to delete cost
        $this->actingAs($BuyerAdmin);
        $this->delete('/admin-delete-cost/' . $cost->id);
        //cost record must be deleted
        $this->assertDatabaseMissing('costs', ['id' => $cost->id]);
        //cost's image records also must be deleted
        $this->assertDatabaseMissing('images', ['id' => $image1->id]);
        $this->assertDatabaseMissing('images', ['id' => $image2->id]);
        //cost's images also must be deleted
        $this->assertFileDoesNotExist(public_path('storage' . $imageNameArray[0]));
        $this->assertFileDoesNotExist(public_path('storage' . $imageNameArray[1]));

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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
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
        $this->prepOrder(1,0);
        $kargo = factory('App\Kargo')->create(['user_id' => $user->id]);
        $cost = factory('App\Cost')->create(['user_id' => $user->id, 'costable_type' => 'App\Kargo', 'costable_id' => $kargo->id]);
        $this->assertInstanceOf(Kargo::class, $cost->costable);
    }

    /** @test */
    public function guests_can_not_access_cost_management()
    {
        $this->get('/costs')->assertRedirect('login');
        $this->get('/costs/create')->assertRedirect('login');
        $this->post('/costs')->assertRedirect('login');
        $this->get('/costs/1')->assertRedirect('login');
        $this->get('/costs/1' . '/edit')->assertRedirect('login');
        $this->patch('/costs/1')->assertRedirect('login');
        $this->delete('/costs/1')->assertRedirect('login');
        $this->get('/admin-index-costs/1')->assertRedirect('login');
        $this->get('/admin-index-single-cost/1/1')->assertRedirect('login');
    }
}
