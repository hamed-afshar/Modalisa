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
        $product = factory('App\Product')->create();
        $status = factory('App\Status')->create();
        $history = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status->id,
        ]);
        $this->get('/histories')->assertSeeText($history->created_at);

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
