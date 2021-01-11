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
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class KargoManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

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
        $kargoList = array();
        for ($i=1;$i<=10;$i++) {
           $this->prepOrder();
           $product = Product::find($i);
           $kargoList[] = $product->id;
        }
        $attributes = factory('App\Kargo')->raw([
            'kargo_list' => $kargoList,
        ]);

        dump(Product::find(1));
        $this->post('/kargos', $attributes);
        $this->assertDatabaseHas('kargos', ['receiver_name' => $attributes['receiver_name']]);
    }

    /** @test
     * super privilege users are able to create a kargo for the given user
     */
    public function super_privilege_users_can_create_kargo_for_the_given_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', ['see-kargos'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepNormalEnv('retailer', ['see-kargos', 'create-kargos'], 0, 1);
        $retailer = Auth::user();
        $this->prepOrder();
        $attributes = factory('App\Kargo')->raw();
        $this->actingAs($BuyerAdmin);
        $this->post('/admin-create-kargo/' . $retailer->id , $attributes);
        $this->assertDatabaseHas('kargos', ['receiver_name' => $attributes['receiver_name']]);
    }


    /** @test
     * super privilege users are able to see a single kargo with all related user and products
     */
    public function super_privilege_users_can_see_a_single_kargo()
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

    /** @test */
    public function each_user_may_have_many_kargos()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $user = Auth::user();
        $this->assertInstanceOf(Kargo::class, $user->kargos->find(1));
    }

    /** @test */
    public function each_kargo_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $kargo = Kargo::find(1);
        $this->assertInstanceOf(User::class, $kargo->user);
    }

    /** @test */
    public function each_kargo_may_have_many_products()
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
    public function each_product_belongs_to_a_kargo()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-kargos'], 0, 1);
        $this->prepOrder();
        $product = Product::find(1);
        $this->assertInstanceOf(Kargo::class, $product->kargo);
    }

    /** @test */
    public function each_kargo_may_have_many_notes()
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
    public function each_note_may_belongs_to_a_kargo()
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
    public function each_kargo_may_have_many_images()
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
    public function each_image_may_belongs_to_a_kargo()
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
