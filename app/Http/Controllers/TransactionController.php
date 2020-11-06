<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /*
     * index transactions
     */
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);
    }

    /*
     * form to create transaction
     * VueJS modal generates this form
     */
    public function create()
    {
        $this->authorize('create', Transaction::class);
    }

    /*
     * store transactions
     */
    public function store()
    {
        $this->authorize('create', Transaction::class);
        Transaction::create(request()->validate([
            'user_id' => 'required',
            'currency' => 'required',
            'amount' => 'required',
            'pic' => 'required',
            'comment' => 'required'
        ]));

    }

}
