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
        ]));
    }

    /*
     * show a single transaction
     * VueJs shows this single transaction
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
    }

    /*
     * edit form
     * VueJs generates this form
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
    }

    /*
     * update transactions
     */
    public function update(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $data = request()->validate([
            'currency' => 'required',
            'amount' => 'required',
            'pic' => 'required',
            'comment' => 'required'
        ]);
        $transaction->update($data);
    }

    /*
     * delete transactions
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
    }

}
