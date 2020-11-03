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
        $this->prepNormalEnv('retailer', 'make-payment', '0', 1);
        $transaction = factory('App\Transactions')->create(['user_id' => auth()->user()->id]);
        $this->get('/transactions')->assertSee($transaction->comment)
            ->assertSee(200);
    }

    /*
     * form is available in vuejs modal
     */
    /** @test */
//    public function form_is_available_to_create_a_transaction()
//    {
//
//    }

    /** @test */
    public function retailers_can_create_transaction()
    {
//        $this->withExceptionHandling();
        $this->prepNormalEnv('retailer','make-payment', 0 ,1);
        $attributes = factory('App\Transactions')->raw(['user_id' => auth()->user()->id]);
        $this->post('/transactions', $attributes);
        $this->assertDatabaseHas('transactions', $attributes);
    }
}
