<?php

namespace App\Http\Controllers;

use App\Traits\uploadTrait;
use App\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Str;


class TransactionController extends Controller
{
    use uploadTrait;

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
     * @param Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);
        $user = Auth::user();
        $data = $request->validate([
            'currency' => 'required',
            'amount' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'comment' => 'required',
        ]);
        if($request->has('image'))
        {
            $image = $request->file('image');
            $name = date('mdYHis') . uniqid();
            $folder = '/uploads/images/';
            $filePath  = $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
        }

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
