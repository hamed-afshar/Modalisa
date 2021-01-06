<?php

namespace Tests\Feature;

use App\History;
use App\Product;
use App\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HistoryManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test
     * users can see histories for a product record
     * history will be created on product creation time.
     * ProductObserver will handle history creation
     */
    public function retailers_can_check_their_product_histories()
    {
        dd('here');
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-histories','create-orders'], 0, 1);
        $status1 = factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $status2 = factory('App\Status')->create([
            'name' => 'arrived',
            'description' => 'arrived to office'
        ]);
        $history1 = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status1->id,
        ]);
        $history2 = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status2->id,
        ]);
        $this->get('/histories/' . $product->id)->assertSeeText($history1->created_at)
            ->assertSeeText($history2->created_at);
        // users can only see their own records
        $this->prepNormalEnv('retailer2', ['see-histories','create-orders'], 0, 1);
        $this->get('/histories/' . $product->id)->assertForbidden();
    }

    /** @test
     * history created on product creation
     */
    public function product_model_observe_to_create_history_on_product_creation()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $status = factory('App\Status')->create();
        $this->prepOrder();
        $this->assertDatabaseHas('histories', ['product_id' => Product::find(1)->id, 'status_id' => $status->id]);
    }

    /** @test
     * history created on story changes
     */
    public function only_BuyerAdmin_can_change_product_history()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', 'change-history', 0, 1);
        $status = factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $this->post('/change-history/' . $product->id . '/' . $status->id);
        $this->assertDatabaseHas('histories', ['product_Id' => $product->id, 'status_id' => $status->id]);
    }

    /** @test */
    public function only_BuyerAdmin_can_delete_histories()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', 'delete-history', 0, 1);
        $status = factory('App\Status')->create();
        $this->prepOrder();
        $history = History::find(1);
        $this->delete('/histories/' . $history->id);
        $this->assertDatabaseMissing('histories', ['id' => $history->id]);
    }

    /** @test
     * one to many relationship
     */
    public function each_status_has_many_histories()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $status = factory('App\Status')->create();
        $this->prepOrder();
        // History automatically is always created on order creation
        $this->assertInstanceOf(History::class, $status->histories->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_history_belongs_to_status()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        // History automatically is always created on order creation
        $history = History::find(1);
        $this->assertInstanceOf(Status::class, $history->status);
    }

    /** @test
     * one to many relationship
     */
    public function each_product_has_many_histories()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $product = Product::find(1);
        $this->assertInstanceOf(History::class, $product->histories->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_history_belongs_to_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $history = History::find(1);
        $this->assertInstanceOf(Product::class, $history->product);
    }

    /** @test */
    public function guests_can_not_access_history_management()
    {
        $this->get('/histories/1')->assertRedirect('login');
        $this->post('/change-history/1/1')->assertRedirect('login');
        $this->delete('/histories/1')->assertRedirect('login');
    }

}
