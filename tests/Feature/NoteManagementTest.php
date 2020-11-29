<?php

namespace Tests\Feature;

use App\Cost;
use App\Customer;
use App\Kargo;
use App\Note;
use App\Order;
use App\Product;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class NoteManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test
     * for Order model
     * polymorphic one-to-many relationship
     */
    public function order_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);
        factory('App\Note')->create(['notable_type' => 'App\Order', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $order->notes->find(1));

    }

    /** @test
     * for Order model
     * polymorphic one-to-many relationship
     */
    public function each_note_may_belongs_to_an_order()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $note = factory('App\Note')->create(['notable_type' => 'App\Order', 'notable_id' => 1]);
        $this->assertInstanceOf(Order::class, $note->notable);
    }

    /** @test
     * for Customer model
     * polymorphic one-to-many relationship
     */
    public function each_customer_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        factory('App\Note')->create(['notable_type' => 'App\Customer', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $customer->notes->find(1));
    }

    /** @test
     * for Customer model
     * polymorphic one-to-many relationship
     */
    public function each_note_may_belong_to_a_customer()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $note = factory('App\Note')->create(['notable_type' => 'App\Customer', 'notable_id' => 1]);
        $this->assertInstanceOf(Customer::class, $note->notable);
    }

    /** @test
     * for Product model
     * polymorphic one-to-many relationship
     */
    public function each_product_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        factory('App\Note')->create(['notable_type' => 'App\Product', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $product->notes->find(1));
    }

    /** @test
     * for Product modal
     * polymorphic one-to-many relationship
     */
    public function each_note_may_belong_to_a_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $note = factory('App\Note')->create(['notable_type' => 'App\Product', 'notable_id' => 1]);
        $this->assertInstanceOf(Product::class, $note->notable);
    }

    /** @test
     * for Transaction model
     * polymorphic one-to-many relationship
     */
    public function each_transaction_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        factory('App\Note')->create(['notable_type' => 'App\Transaction', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $transaction->notes->find(1));
    }

    /** @test
     * for Transaction model
     * polymorphic one-to-many relationship
     */
    public function each_note_may_belong_to_a_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $note = factory('App\Note')->create(['notable_type' => 'App\Transaction', 'notable_id' => 1]);
        $this->assertInstanceOf(Transaction::class, $note->notable);

    }

    /** @test
     * for Kargo model
     * polymorphic one-to_many_ relationship
     */
    public function each_kargo_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $kargo = Kargo::find(1);
        factory('App\Note')->create(['notable_type' => 'App\Kargo', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $kargo->notes->find(1));
    }

    /** @test
     * for Cost model
     * polymorphic relationship
     */
    public function each_cost_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $cost = factory('App\Cost')->create(['costable_type' => 'App\Product', 'costable_id' => $product->id]);
        factory('App\Note')->create(['notable_type' => 'App\Cost', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $cost->notes->find(1));
    }

    /** @test
     * for Cost model
     * polymorphic relationship
     */
    public function each_note_may_belong_to_a_cost()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product  = Product::find(1);
        factory('App\Cost')->create(['costable_type' => 'App\Product', 'costable_id' => $product->id]);
        $note= factory('App\Note')->create(['notable_type' => 'App\Cost', 'notable_id' => 1]);
        $this->assertInstanceOf(Cost::class, $note->notable);
    }
}
