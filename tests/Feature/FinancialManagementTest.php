<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FinancialManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;
   /** @test */
    public function retailers_can_see_their_transactions()
    {
        $this->withExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', '0', 1);
        $transaction = factory('App\Transactions')->create(['user_id' => auth()->user()->id]);
        $this->get('/transactions')->assertSee($transaction->comment)
            ->assertSee(200);
    }
}
