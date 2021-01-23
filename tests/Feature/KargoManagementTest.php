<?php

namespace Tests\Feature;

use App\Image;
use App\Kargo;
use App\Note;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class KargoManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * function to prepare kargo
     */
    public function prepKargo()
    {
        $kargoList = array();
        for ($i = 1; $i <= 10; $i++) {
            $this->prepOrder();
            $product = Product::find($i);
            $kargoList[] = $product->id;
        }
        $attributes = factory('App\Kargo')->raw([
            'kargo_list' => $kargoList,
        ]);
        $this->post('/kargos', $attributes);
    }

    /** @test
     * users should have see-kargos permission to be allowed
     * users can only see their own records
     * kargo contains all related products
     */
    public function users_can_see_their_kargos()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $kargo = Kargo::find(1);
        $product = Product::find(1);
        $this->get('/kargos')->assertSeeText($kargo->receiver_name)
            ->assertSeeText($product->link);
        // users can not see other users records
        $this->prepNormalEnv('retailer2', ['see-kargos'], 0, 1);
        $this->get('/kargos')->assertDontSeeText($kargo->receiver_name);
    }

    /** @test
     * super privilege users are able to see all kargos
     */
    public function super_privilege_users_can_see_all_kargos()
    {
        $this->prepNormalEnv('BuyerAdmin', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $user = Auth::user();
        $kargo = Kargo::find(1);
        $this->get('/admin-index-kargos/')->assertSeeText($kargo->receiver_name)
            ->assertSeeText($user->name);
        // other users are not allowed to index all kargos
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->get('/admin-index-kargos/')->assertForbidden();
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_create_a_kargo()
    {

    }

    /** @test
     *  users with create-kargos permission can create kargo
     */
    public function users_with_create_kargo_permission_can_create_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos', 'create-kargos'], 0, 1);
        $this->prepKargo();
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $this->assertDatabaseHas('kargos', ['id' => $lastKargoId]);
        //product's kargo id must be equal to the created kargo id
        $product = Product::find(5);
        $this->assertEquals($product->kargo_id, $lastKargoId);
    }


    /** @test
     * super privilege users are able to create a kargo for the given user
     */
    public function super_privilege_users_can_create_kargo_for_the_given_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', ['see-kargos', 'create-kargos'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-kargos', 'create-kargos'], 0, 1);
        $retailer = Auth::user();
        // retailer create 10 products
        $kargoList = array();
        for ($i = 1; $i <= 10; $i++) {
            $this->prepOrder();
            $product = Product::find($i);
            $kargoList[] = $product->id;
        }
        $attributes = factory('App\Kargo')->raw([
            'kargo_list' => $kargoList,
        ]);
        //acting as BuyerAdmin to create the kargo
        $this->actingAs($BuyerAdmin);
        $this->post('/admin-create-kargo/' . $retailer->id, $attributes);
        //record for the created kargo must exist in db
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $this->assertDatabaseHas('kargos', ['id' => $lastKargoId, 'user_id' => $retailer->id]);
        //product's kargo id must be equal to the created kargo id
        $product = Product::find(5);
        $this->assertEquals($product->kargo_id, $lastKargoId);
    }

    /** @test
     * number of items in any kargo should meet the minimum limit based on user's subscription plan
     */
    public function each_karo_must_contain_minimum_number_of_items()
    {
        $this->prepNormalEnv('retailer', ['see-kargos', 'create-kargos'], 0, 1);
        $kargoList = array();
        for ($i = 1; $i <= 10; $i++) {
            $this->prepOrder();
            $product = Product::find($i);
            $kargoList[] = $product->id;
        }
        $attributes = factory('App\Kargo')->raw([
            'kargo_list' => $kargoList,
        ]);
        // if number of products in kargo is less than kargo_limit, then new record will not be created in db
        //create a subscription with kargo limit value of 20, but add 10 products to create a new kargo record
        $newSubscription = factory('App\Subscription')->create(['kargo_limit' => 20]);
        Auth::user()->subscription()->associate($newSubscription);
        Auth::user()->save();
        Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $this->post('/kargos', $attributes);
        $product = Product::find(5);
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $this->assertDatabaseMissing('kargos', ['id' => $lastKargoId + 1]);
        $this->assertNotEquals($product->id, $lastKargoId + 1);
    }

    /** @test
     * super privilege users are able to confirm the kargo
     */
    public function super_privilege_users_can_confirm_kargos()
    {
        $this->prepNormalEnv('retailer', ['create-kargos', 'see-kargos'], 0, 1);
        $retailer = Auth::user();
        $this->prepNormalEnv('BuyerAdmin', ['create-kargos', 'see-kargos'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->actingAs($retailer);
        $this->prepKargo();
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $this->assertDatabaseHas('kargos', ['id' => $lastKargoId]);
        //acting as BuyerAdmin to confirm this kargo
        $this->actingAs($BuyerAdmin);
        $kargo = Kargo::find($lastKargoId);
        $confirmAttributes = [
            'confirmed' => 1,
            'weight' => 100
        ];
        $this->patch('/confirm-kargo/' . $kargo->id, $confirmAttributes);
        $this->assertDatabaseHas('kargos', [
            'id' => $lastKargoId,
            'weight' => $confirmAttributes['weight'],
            'confirmed' => $confirmAttributes['confirmed']
        ]);
        //other users are not allowed to confirm kargos
        $this->actingAs($retailer);
        $this->patch('/confirm-kargo/' . $kargo->id, $confirmAttributes)->assertForbidden();
    }

    /** @test
     * only super privilege users can upload pictures
     */
    public function image_can_be_uploaded_on_confirmation()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-kargos', 'see-kargos'], 0, 1);
        $retailer = Auth::user();
        $this->prepNormalEnv('BuyerAdmin', ['create-kargos', 'see-kargos'], 0, 1);
        $BuyerAdmin = Auth::user();
        // first create a kargo as a retailer
        $this->actingAs($retailer);
        $this->prepKargo();
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $this->assertDatabaseHas('kargos', ['id' => $lastKargoId]);
        // acting as BuyerAdmin to confirm this kargo
        $this->actingAs($BuyerAdmin);
        $confirmAttributes = [
            'confirmed' => 1,
            'weight' => 100,
            'image' => UploadedFile::fake()->create('kargo-pic.jpg')
        ];
        $kargo = Kargo::find($lastKargoId);
        $this->patch('/confirm-kargo/' . $kargo->id, $confirmAttributes);
        $this->assertDatabaseHas('kargos', [
            'id' => $lastKargoId,
            'weight' => $confirmAttributes['weight'],
            'confirmed' => $confirmAttributes['confirmed']
        ]);
        $this->assertDatabaseHas('images', ['imagable_id' => $kargo->id, 'imagable_type' => 'App\Kargo']);
        $imageName = $kargo->images()->where('imagable_id', $kargo->id)->value('image_name');
        $this->assertFileExists(public_path('storage' . $imageName));
    }

    /** @test
     * users can see a single kargo with related products
     * users should have see-kargos permission to be allowed
     */
    public function users_can_see_a_single_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-kargos', 'see-kargos'], 0, 1);
        $this->prepKargo();
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $kargo = Kargo::find($lastKargoId);
        $product = Product::find(5);
        $this->get($kargo->path())->assertSeeText($kargo->receiver_name)->assertSeeText($product->link);
    }

    /** @test
     * super privilege users are able to see a single kargo with all related user and products
     */
    public
    function super_privilege_users_can_see_a_single_kargo()
    {
        $this->prepNormalEnv('BuyerAdmin', ['see-kargos'], 0, 1);
        $user = Auth::user();
        $this->prepOrder();
        $product = Product::find(1);
        $kargo = Kargo::find(1);
        $this->get('/admin-index-single-kargo')->assertSeeText($kargo->reciver_name)
            ->assertSeeText($user->name)->assertSeeText($product->link);
        // other users are not allowed to index a single kargo
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->get('/admin-index-single-kargo')->assertForbidden();
    }

    /** @test
     *  users can update not confirmed kargos
     *  users must have create-kargos permission to be allowed
     *  users can update kargo details, add products and delete products
     */
    public function users_can_update_not_confirm_kargos()
    {
        $this->prepNormalEnv('retailer', ['create-kargos', 'see-kargos'], 0, 1);
        $retailer = Auth::user();
        // first create a kargo as a retailer
        $this->actingAs($retailer);
        $this->prepKargo();
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $this->assertDatabaseHas('kargos', ['id' => $lastKargoId]);
        // users can update kargo details
        $updateAttributes = [
            'receiver_name' => 'new ramin',
            'receiver_tel' => '0923333333',
            'receiver_address' => 'new address',
            'sending_date' => '2020-10-20'
        ];
        $kargo = Kargo::find($lastKargoId);
        $this->patch($kargo->path(), $updateAttributes);
        $this->assertDatabaseHas('kargos', $updateAttributes);
        //users can only update their own records
        $this->prepNormalEnv('retailer2', ['create-kargos', 'see-kargos'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2);
        $this->patch($kargo->path(), $updateAttributes)->assertForbidden();
    }

    /** @test */
    public function users_can_not_update_confirmed_kargos()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-kargos', 'see-kargos'], 0, 1);
        $retailer = Auth::user();
        // first create a kargo as a retailer
        $this->actingAs($retailer);
        $this->prepKargo();
        //acting as BuyerAdmin to confirm the kargo
        $this->prepNormalEnv('BuyerAdmin', ['create_kargos', 'see-kargos'], 0 , 1);
        $BuyerAdmin = Auth::user();
        $this->actingAs($BuyerAdmin);
        $lastKargoId = Kargo::latest()->orderBy('id', 'DESC')->first()->id;
        $kargo = Kargo::find($lastKargoId);
        $confirmAttributes = [
            'confirmed' => 1,
            'weight' => 100
        ];
        $this->patch('/confirm-kargo/' . $kargo->id, $confirmAttributes);
        $this->assertDatabaseHas('kargos', [
            'id' => $lastKargoId,
            'weight' => $confirmAttributes['weight'],
            'confirmed' => $confirmAttributes['confirmed']
        ]);
        //users can not update kargo records if they were confirmed
        $this->actingAs($retailer);
        $updateAttributes = [
            'receiver_name' => 'new ramin',
            'receiver_tel' => '0923333333',
            'receiver_address' => 'new address',
            'sending_date' => '2020-10-20'
        ];
        $this->patch($kargo->path(), $updateAttributes)->assertForbidden();
    }

    /** @test */
    public
    function each_user_may_have_many_kargos()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $user = Auth::user();
        $this->assertInstanceOf(Kargo::class, $user->kargos->find(1));
    }

    /** @test */
    public
    function each_kargo_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $kargo = Kargo::find(1);
        $this->assertInstanceOf(User::class, $kargo->user);
    }

    /** @test */
    public
    function each_kargo_may_have_many_products()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $order = Order::find(1);
        $kargo = Kargo::find(1);
        factory('App\Product')->create([
            'order_id' => $order->id,
            'kargo_id' => $kargo->id
        ]);
        $this->assertInstanceOf(Product::class, $kargo->products->find(1));
    }

    /** @test */
    public
    function each_product_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $product = Product::find(1);
        $this->assertInstanceOf(Kargo::class, $product->kargo);
    }

    /** @test */
    public
    function each_kargo_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $kargo = Kargo::find(1);
        factory('App\Note')->create([
            'notable_type' => 'App\Kargo',
            'notable_id' => $kargo->id
        ]);
        $this->assertInstanceOf(Note::class, $kargo->notes->find(1));
    }

    /** @test */
    public
    function each_note_may_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $kargo = Kargo::find(1);
        $note = factory('App\Note')->create([
            'notable_type' => 'App\Kargo',
            'notable_id' => $kargo->id
        ]);
        $this->assertInstanceOf(Kargo::class, $note->notable);

    }

    /** @test */
    public
    function each_kargo_may_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $user = Auth::user();
        $kargo = Kargo::find(1);
        factory('App\Image')->create([
            'user_id' => $user->id,
            'imagable_type' => 'App\Kargo',
            'imagable_id' => $kargo->id,
            'image_name' => 'kargo1.jpg'
        ]);
        $this->assertInstanceOf(Image::class, $kargo->images->find(1));
    }

    /** @test */
    public
    function each_image_may_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $user = Auth::user();
        $kargo = Kargo::find(1);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'imagable_type' => 'App\Kargo',
            'imagable_id' => $kargo->id,
            'image_name' => 'kargo1.jpg'
        ]);
        $this->assertInstanceOf(Kargo::class, $image->imagable);
    }
}
