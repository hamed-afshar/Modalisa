<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class OrderTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_a_path() 
    {
        $this->withoutExceptionHandling();
        $order = factory('App\Order')->create();
        $this->assertEquals('/orders/' . $order->id, $order->path());
        
    }
}
