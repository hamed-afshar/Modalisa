<?php

namespace Tests\Feature;

use App\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteManagementTest extends TestCase
{
    /** @test
     * polymorphic relationship
     */
    public function order_may_have_many_notes()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0 , 1);
        factory('App\Status')->create();
        $this->prepOrder();
        $order = Order::find(1);

    }
}
