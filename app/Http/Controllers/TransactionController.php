<?php

namespace App\Http\Controllers;

use App\Traits\ImageTrait;
use App\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;
use Psy\Util\Str;


class TransactionController extends Controller
{
    use ImageTrait;

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
        // First transaction must be created and get transaction_id to be used in image creation model.
        $this->authorize('create', Transaction::class);
        $user = Auth::user();
        $request->validate([
            'currency' => 'required',
            'amount' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'comment' => 'required',
        ]);
        // Prepare transaction data
        $transactionData = [
            'currency' => $request->input('currency'),
            'amount' => $request->input('amount'),
            'comment' => $request->input('comment'),
        ];
        // Create a transaction
        $transaction = $user->transactions()->create($transactionData);
        // If image is included on transaction creation time, then image uploads and model be created in db
        if ($request->has('image')) {
            $image = $request->file('image');
            $imageName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageName);
            $imageData = [
                // imagable_type always remains App\Transaction
                'imagable_type' => 'App\Transaction',
                'imagable_id' => $transaction->id,
                'image_name' => $filePath
            ];
            $user->images()->create($imageData);
        }
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
    public function update(Request $request, Transaction $transaction)
    {

        $this->authorize('update', $transaction);
        request()->validate([
            'currency' => 'required',
            'amount' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'comment' => 'required'
        ]);

        /*
         *  if request includes image then name will change to the new name also delete the old file
         *  if request does not include image then name will remain intact
         */
        $filePath = null;
        if ($request->has('image')) {
            $image = $request->file('image');
            $imageNewName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageNewName);
            $this->deleteOne('public', $request->input('image_name'));
        }
        $data = [
            'currency' => $request->input('currency'),
            'amount' => $request->input('amount'),
            'comment' => $request->input('comment'),
        ];
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

    /**
     * upload image for transaction model
     */
    public function uploadImage(Request $request)
    {
//        $transaction = Transaction::find($request->input('id'));
        dd($request);
        $this->authorize('update', $transaction);
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $image = $request->file('image');
        $name = date('mdYHis') . uniqid();
        $folder = '/images/';
        $this->uploadOne($image, $folder, 'public', $name);
        $data = [
            'image_name' => $name
        ];
        $transaction->update($data);
    }
}
