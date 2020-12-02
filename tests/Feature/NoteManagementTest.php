<?php

namespace Tests\Feature;

use App\Cost;
use App\Customer;
use App\Kargo;
use App\Note;
use App\Order;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Generator\DefaultTimeGenerator;
use Tests\TestCase;

class NoteManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function users_can_only_access_to_their_own_notes()
    {
        $this->prepNormalEnv('retailer', 'see-notes', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user1 = Auth::user();
        $order = Order::find(1);
        $note = factory('App\Note')->create(['user_id' => $user1->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
        $user2 = factory('App\User')->create();
        $this->actingAs($user2);
        $this->get('/notes/' . $note->id)->assertForbidden();
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_create_a_note()
    {

    }

    /** @test */
    public function user_can_create_a_note()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'create-notes', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
        $this->post('/notes', $attributes);
        $this->assertDatabaseHas('notes', ['id' => 1]);
    }

    /** @test */
    public function title_is_required()
    {
        $this->prepNormalEnv('retailer', 'create-notes', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id, 'title' => '']);
        $this->post('/notes', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function body_is_required()
    {
        $this->prepNormalEnv('retailer', 'create-notes', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id, 'body' => '']);
        $this->post('/notes', $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function notable_type_is_required()
    {
        $this->prepNormalEnv('retailer', 'create-notes', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw(['user_id' => $user->id, 'notable_type' => '', 'notable_id' => $order->id]);
        $this->post('/notes', $attributes)->assertSessionHasErrors('notable_type');
    }

    /** @test */
    public function notable_id_is_required()
    {
        $this->prepNormalEnv('retailer', 'create-notes', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => '']);
        $this->post('/notes', $attributes)->assertSessionHasErrors('notable_id');
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_a_note()
    {

    }

    /** @test */
    public function users_can_not_update_notes()
    {
        $this->prepNormalEnv('retailer', 'create-notes', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        $user = Auth::user();
        $note = factory('App\Note')->create(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
        $this->patch($note->path())->assertForbidden();
    }




    /** @test */
    public function user_can_see_all_notes_related_to_a_model()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'see-notes', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user = Auth::user();
        $order = Order::find(1);
        $note = factory('App\Note')->create(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
        $this->get('/notes/' . $note->id)->assertSeeText($note->body);
    }

    /** @test
     * one to many relationship
     */
    public function each_user_can_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        $user = Auth::user();
        factory('App\Note')->create(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
        $this->assertInstanceOf(Note::class, $user->notes->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_note_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $user = Auth::user();
        $order = Order::find(1);
        $note = factory('App\Note')->create(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
        $this->assertInstanceOf(User::class, $note->user);
    }


    /** all relationship related to Note model should be tested
     * Models that have a polymorphic relationship with Note are::
     * Order, Customer, Product, Transaction, Kargo, Cost
     */

    /** @test
     * for Order model
     * polymorphic one-to-many relationship
     */
    public
    function order_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Order', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $order->notes->find(1));

    }

    /** @test
     * for Order model
     * polymorphic one-to-many relationship
     */
    public
    function each_note_may_belongs_to_an_order()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $note = factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Order', 'notable_id' => 1]);
        $this->assertInstanceOf(Order::class, $note->notable);
    }

    /** @test
     * for Customer model
     * polymorphic one-to-many relationship
     */
    public
    function each_customer_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Customer', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $customer->notes->find(1));
    }

    /** @test
     * for Customer model
     * polymorphic one-to-many relationship
     */
    public
    function each_note_may_belong_to_a_customer()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $note = factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Customer', 'notable_id' => 1]);
        $this->assertInstanceOf(Customer::class, $note->notable);
    }

    /** @test
     * for Product model
     * polymorphic one-to-many relationship
     */
    public
    function each_product_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Product', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $product->notes->find(1));
    }

    /** @test
     * for Product modal
     * polymorphic one-to-many relationship
     */
    public
    function each_note_may_belong_to_a_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $note = factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Product', 'notable_id' => 1]);
        $this->assertInstanceOf(Product::class, $note->notable);
    }

    /** @test
     * for Transaction model
     * polymorphic one-to-many relationship
     */
    public
    function each_transaction_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Transaction', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $transaction->notes->find(1));
    }

    /** @test
     * for Transaction model
     * polymorphic one-to-many relationship
     */
    public
    function each_note_may_belong_to_a_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $note = factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Transaction', 'notable_id' => 1]);
        $this->assertInstanceOf(Transaction::class, $note->notable);

    }

    /** @test
     * for Kargo model
     * polymorphic one-to_many_ relationship
     */
    public
    function each_kargo_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $kargo = Kargo::find(1);
        factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Kargo', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $kargo->notes->find(1));
    }

    /** @test
     * for Cost model
     * polymorphic relationship
     */
    public
    function each_cost_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $cost = factory('App\Cost')->create(['costable_type' => 'App\Product', 'costable_id' => $product->id]);
        factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Cost', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $cost->notes->find(1));
    }

    /** @test
     * for Cost model
     * polymorphic relationship
     */
    public
    function each_note_may_belong_to_a_cost()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        factory('App\Cost')->create(['costable_type' => 'App\Product', 'costable_id' => $product->id]);
        $note = factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Cost', 'notable_id' => 1]);
        $this->assertInstanceOf(Cost::class, $note->notable);
    }
}
