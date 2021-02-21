<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountingManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     * retailer's account will deposit after the money transfer
     * first SystemAdmin should confirm the transfer
     */
    public function deposit_retailers_account_after_money_transfer_is_confirmed_by_by_SystemAdmin()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-transactions'], 0, 1);
        $transaction = factory('App\Transaction')->create();
    }
}
