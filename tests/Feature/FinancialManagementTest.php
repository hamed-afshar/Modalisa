<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FinancialManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test
     * make sure that retailers can not change or access other retailers transactions
     * for all actions including update,delete
     */
    public function retailers_only_can_access_to_their_own_resources()
    {
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
        $this->delete($transaction->path(), $newAttributes)->assertForbidden();
    }

    /** @test
     * retailers can not make any changes to confirmed transactions
     * including edit and delete
     */
    public function retailer_can_not_delete_or_update_confirmed_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id, 'confirmed' => 1]);
        $this->patch($transaction->path())->assertForbidden();
        $this->delete($transaction->path())->assertForbidden();
    }

    /** @test
     * retailers can not confirm the transactions
     * only SystemAdmin is able to confirm transactions
     */
    public function retailers_can_not_confirm_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $newAttributes = [
            'confirmed' => 1
        ];
        $this->patch('/transactions/confirm/' . $transaction->id, $newAttributes)->assertForbidden();
    }

    /** @test */
    public function retailers_can_see_their_transactions()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->get('/transactions')->assertSeeText($transaction->pic);
    }

    /*
     * this should be tested in VueJs
     */

    public function form_is_available_to_create_a_transaction()
    {

    }

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
    public function retailer_can_see_a_single_transaction()
    {
        $this->prepNormalEnv('retailers', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->get($transaction->path())->assertSeeText($transaction->pic);
    }

    /*
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_a_transaction()
    {

    }

    /** @test */
    public function retailer_can_update_not_confirmed_transactions()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
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

    /** @test */
    public function retailer_can_delete_not_confirmed_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->delete($transaction->path());
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    /** @test */
    public function transaction_belongs_to_a_user()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $transaction->user);
    }

    /** @test */
    public function user_can_have_many_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $user = Auth::user();
        factory('App\Transaction')->create(['user_id' => $user->id]);
        $transaction = $user->transactions->find(1);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /** @test */
    public function guests_can_not_access_transaction_management()
    {
        $this->get('/transactions')->assertRedirect('login');
        $this->get('/transactions/create')->assertRedirect('login');
        $this->post('/transactions')->assertRedirect('login');
        $this->get('/transactions/1')->assertRedirect('login');
        $this->get('/transactions/1' . '/edit')->assertRedirect('login');
        $this->patch('/transactions/1')->assertRedirect('login');
        $this->delete('/transactions/1')->assertRedirect('login');
    }
}
