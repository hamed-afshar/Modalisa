<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FinancialManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test
     * this test is not show anything in blade files because
     * transaction information will be available in vuejs
     */
//    public function retailers_can_see_their_transactions()
//    {
//        $this->withoutExceptionHandling();
//        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
//        $transaction = factory('App\Transaction')->create(['user_id' => auth()->user()->id]);
//        $this->get('/transactions')->assertSee($transaction->comment)
//            ->assertSee(200);
//    }

    /*
     * create form availability should be tested in VueJs
     */

    /** @test */
    public function retailers_can_create_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id]);
        $this->post('/transactions', $attributes);
        $this->assertDatabaseHas('transactions', $attributes);
    }

    /** @test */
    public function user_id_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('user_id');
    }
}
