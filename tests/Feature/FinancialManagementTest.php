<?php

namespace Tests\Feature;

use App\Transaction;
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
     * form_is_available_to_create_a_transaction
     * this should be tested in VueJs
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

    /** @test */
    public function currency_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'currency' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('currency');
    }

    /** @test */
    public function amount_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'amount' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('amount');
    }

    /** @test */
    public function pic_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'pic' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('pic');
    }

    /** @test */
    public function comment_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'comment' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasNoErrors();
    }

    /*
     * retailer_can_see_a_single_transaction
     * this should be tested in VueJs
     */

    /** @test */
    public function retailer_can_edit_a_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', 0 , 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $newAttributes = [
            'currency' => 'USD',
            'amount' => '9999',
            'pic' => 'new_link',
            'comment' => 'new comment'
        ];
        $this->patch($transaction->path(), $newAttributes);
        $this->assertDatabaseHas('transactions', $newAttributes);
    }



}
