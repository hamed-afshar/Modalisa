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
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class NoteManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** @test
     * it is not necessary for users to see all notes
     * users should only see notes related to a specific record
     */
    public function user_can_see_all_notes_related_to_a_specific_object()
    {
        $this->prepNormalEnv('retailer', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1, 0);
        $user = Auth::user();
        $order = Order::find(1);
        $note1 = factory('App\Note')->create([
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => $order->id,
            'body' => 'first note'
        ]);

        $note2 = factory('App\Note')->create([
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => $order->id,
            'body' => 'second note'
        ]);
        $model = 'App\Order';
        $id = $order->id;
        $this->get('/notes/' . $id . '/' . $model)->assertSeeText($note1->body);
        $this->get('/notes/' . $id . '/' . $model)->assertSeeText($note2->body);
        //users can only index their own records
        $this->prepNormalEnv('retailer2', ['create-notes', 'see-notes'], 0 , 1);
        $this->get('/notes/' . $id . '/' . $model)->assertDontSeeText($note1->body);
        $this->get('/notes/' . $id . '/' . $model)->assertDontSeeText($note2->body);
    }


    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_create_a_note()
    {

    }

    /** @test
     * users should have create-notes permission to be allowed
     */
    public function users_can_create_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw([
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => $order->id
        ]);
        $this->post('/notes', $attributes);
        $this->assertDatabaseHas('notes', $attributes);
    }

    /** @test */
    public function title_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1, 0);
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw([
            'title' => '',
            'body' => 'note body',
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => $order->id
        ]);
        $this->post('/notes', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function body_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw([
            'title' => 'note title',
            'body' => '',
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => $order->id
        ]);
        $this->post('/notes', $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function notable_type_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw([
            'title' => 'some title',
            'body' => 'note body',
            'user_id' => $user->id,
            'notable_type' => '',
            'notable_id' => $order->id
        ]);
        $this->post('/notes', $attributes)->assertSessionHasErrors('notable_type');
    }

    /** @test */
    public function notable_id_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $user = Auth::user();
        $order = Order::find(1);
        $attributes = factory('App\Note')->raw([
            'title' => 'note title',
            'body' => 'note body',
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => ''
        ]);
        $this->post('/notes', $attributes)->assertSessionHasErrors('notable_id');;
    }

    /** @test
     * users should have see-notes permission to be allowed
     * users can only see their own notes
     */
    public function users_can_see_a_single_note()
    {
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $order = Order::find(1);
        $user = Auth::user();
        $note = factory('App\Note')->create([
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => $order->id
        ]);
        $this->get($note->path())->assertSeeText($note->body);
        // users can only see their own records
        $this->prepNormalEnv('retailer2', ['create-notes', 'see-notes'], 0 , 1);
        $this->get($note->path())->assertForbidden();
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_a_note()
    {

    }

    /** @test
     * users can not update notes
     */
    public function users_can_not_update_notes()
    {
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $order = Order::find(1);
        $user = Auth::user();
        $note = factory('App\Note')->create([
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id' => $order->id
        ]);
        $this->patch($note->path())->assertForbidden();
    }

    /** @test */
    public function users_can_delete_notes()
    {
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes', 'delete-notes'], 0 , 1);
        $retailer1 = Auth::user();
        factory('App\Status')->create();
        $this->prepOrder(1,0);
        $order = Order::find(1);
        $user = Auth::user();
        $note = factory('App\Note')->create([
            'user_id' => $user->id,
            'notable_type' => 'App\Order',
            'notable_id'=> $order->id
        ]);
        //users can not delete other user's notes
        $this->prepNormalEnv('retailer2', ['create-notes', 'see-notes', 'delete-notes'], 0 , 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2);
        $this->delete($note->path())->assertForbidden();
        //users can delete their own notes
        $this->actingAs($retailer1);
        $this->delete($note->path());
        $this->assertDatabaseMissing('notes', ['id' => $note->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
    }

    /** all relationship related to Note model should be tested
     * model with a normal relationship to Note is: User
     * Models that have a polymorphic relationship with Note are:
     * Order, Customer, Product, Transaction, Kargo, Cost
     */

    /** @test
     * one to many relationship
     */
    public function each_user_can_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $user = Auth::user();
        $order = Order::find(1);
        $note = factory('App\Note')->create(['user_id' => $user->id, 'notable_type' => 'App\Order', 'notable_id' => $order->id]);
        $this->assertInstanceOf(User::class, $note->user);
    }

    /** @test
     * for Order model
     * polymorphic one-to-many relationship
     */
    public
    function each_order_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $product = Product::find(1);
        $cost = factory('App\Cost')->create(['user_id' => Auth::user()->id, 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
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
        $this->prepNormalEnv('retailer1', ['create-notes', 'see-notes'], 0 , 1);
        $this->prepOrder(1,0);
        $product = Product::find(1);
        factory('App\Cost')->create(['user_id' => Auth::user()->id, 'costable_type' => 'App\Product', 'costable_id' => $product->id]);
        $note = factory('App\Note')->create(['user_id' => Auth::user()->id, 'notable_type' => 'App\Cost', 'notable_id' => 1]);
        $this->assertInstanceOf(Cost::class, $note->notable);
    }

    /** @test */
    public function guests_can_not_access_note_management()
    {
        $this->get('/notes/1/2')->assertRedirect('login');
        $this->get('/notes/create')->assertRedirect('login');
        $this->post('/notes')->assertRedirect('login');
        $this->get('/notes/1')->assertRedirect('login');
        $this->get('/notes/1' . '/edit')->assertRedirect('login');
        $this->patch('/notes/1')->assertRedirect('login');
        $this->delete('/notes/1')->assertRedirect('login');
    }
}
