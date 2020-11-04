<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /*
     * index transactions
     */
    public function index()
    {
        $this->authorize('viewAny', Transactions::class);
    }

}
