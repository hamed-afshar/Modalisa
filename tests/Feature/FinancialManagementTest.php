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

    /*
     * make sure that retailers can not change or access other retailers transactions
     * for all actions including index,create,store,edit,update,delete
     * we only test one of these actions
     */
    /** @test */
    public function retailers_only_can_access_to_their_own_resources()
    {
        $this->withoutExceptionHandling();
        $subscription = factory('App\Subscription')->create();
        $role = factory('App\Role')->create(['name' => 'retailer']);
        $permission = factory('App\Permission')->create(['name' => 'make-payment']);
        $user = factory('App\User')->create(['confirmed' => 1, 'locked' => 0]);
        $newUser = factory('App\User')->create(['confirmed' => 1, 'locked' => 0]);
        $role->changeRole($user);
        $role->changeRole($newUser);
        $role->allowTo($permission);
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        $newAttributes = [
            'currency' => 'USD',
            'amount' => '9999',
            'pic' => 'new_link',
            'comment' => 'new comment'
        ];
        $this->actingAs($newUser);
        $this->patch($transaction->path(), $newAttributes)->assertForbidden();
    }

    /*
     * retailers_can_see_their_transactions
     */

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
