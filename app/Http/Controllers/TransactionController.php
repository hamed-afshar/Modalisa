<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * index transactions
     */
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);
        return Auth::user()->transactions;
    }

    /**
     * form to create transaction
     * VueJS modal generates this form
     */
    public function create()
    {
        $this->authorize('create', Transaction::class);
    }

    /**
     * store transactions
     */
    public function store()
    {
        $this->authorize('create', Transaction::class);
        $user = Auth::user();
        $data = request()->validate([
            'currency' => 'required',
            'amount' => 'required',
            'pic' => 'required',
            'comment' => 'required',
        ]);
        $user->transactions()->create($data);
    }

    /**
     * show a single transaction
     * VueJs shows this single transaction
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        return Auth::user()->transactions->find($transaction);
    }

    /**
     * edit form
     * VueJs generates this form
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
    }

    /**
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

    /**
     * delete transactions
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();
    }

    /**
     * confirm transactions
     */
    public function confirm(Transaction $transaction)
    {
        $this->authorize('confirm', $transaction);
        $data = request()->validate([
            'confirmed' => 'required'
        ]);
        $transaction->update($data);
    }
}
