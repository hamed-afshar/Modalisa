<?php

namespace Tests\Feature;

use App\Product;
use App\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatusManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test
     * one to many relation ship
     */
    public function status_can_have_many_products()
    {
        $this->withoutExceptionHandling();
        $status = factory('App\Status')->create();
        factory('App\Product')->create(['status_id' => $status->id]);
        $product = $status->products->find(1);
        $this->assertInstanceOf(Product::class, $product);
    }

    /** @test
     * one to many relationship
     */
    public function product_belongs_to_a_status()
    {
        $this->withoutExceptionHandling();
        $status = factory('App\Status')->create();
        $product = factory('App\Product')->create(['status_id' => $status->id]);
        $this->assertInstanceOf(Status::class, $product->status);

    }
}
