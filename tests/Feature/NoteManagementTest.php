<?php

namespace Tests\Feature;

use App\Customer;
use App\Note;
use App\Order;
use App\Product;
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
        $customer = factory('App\Customer')->create(['user_id'=>Auth::user()->id]);
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
        factory('App\Customer')->create(['user_id'=>Auth::user()->id]);
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
        $this->prepNormalEnv('retailer', 'make-order', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        factory('App\Note')->create(['notable_type'=>'App\Product', 'notable_id' => 1]);
        $this->assertInstanceOf(Note::class, $product->notes->find(1));
    }

    /** @test
     * for Product modal
     * polymorphic one-to-many relationship
     */
    public function each_note_may_belong_to_a_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $note = factory('App\Note')->create(['notable_type' => 'App\Product', 'notable_id' => 1]);
        $this->assertInstanceOf(Product::class, $note->notable);
    }

}
