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

    /** @test */
    public function retailers_can_check_their_product_histories()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'check-status', 0, 1);
        $this->prepOrder();
        $product = Product::find(1);
        $status1 = factory('App\Status')->create();
        $history1 = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status1->id,
        ]);
        $status2 = factory('App\Status')->create([
            'name' => 'arrived',
            'description' => 'arrived to office'
        ]);
        $history2 = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status2->id,
        ]);
        $this->get('/histories/' . $product->id)->assertSeeText($history1->created_at)
            ->assertSeeText($history2->created_at);
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

    /** @test
     * one to many relationship
     */
    public function each_status_has_many_histories()
    {
        $this->withoutExceptionHandling();
        $product = factory('App\Product')->create();
        $status = factory('App\Status')->create();
        $history = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status->id
        ]);
        $this->assertInstanceOf(History::class, $status->histories->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_history_belongs_to_status()
    {
        $this->withoutExceptionHandling();
        $product = factory('App\Product')->create();
        $status = factory('App\Status')->create();
        $history = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status->id
        ]);
        $this->assertInstanceOf(Status::class, $history->status);
    }

    /** @test
     * one to many relationship
     */
    public function each_product_has_many_histories()
    {
        $this->withoutExceptionHandling();
        $product = factory('App\Product')->create();
        $status = factory('App\Status')->create();
        $history = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status->id
        ]);
        $this->assertInstanceOf(History::class, $product->histories->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_history_belongs_to_product()
    {
        $this->withoutExceptionHandling();
        $product = factory('App\Product')->create();
        $status = factory('App\Status')->create();
        $history = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status->id
        ]);
        $this->assertInstanceOf(Product::class, $history->product);
    }


}
