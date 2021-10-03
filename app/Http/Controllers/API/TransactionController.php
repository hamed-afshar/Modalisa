<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Traits\ImageTrait;
use App\Transaction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use ImageTrait;

    /**
     * index transactions
     * users with see-transactions permission can see transactions
     * users can only see transaction records belong to them
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);
        //users can only index transactions belongs to them
        $transactions =  Auth::user()->transactions;
        return response(['transactions' => TransactionResource::collection($transactions), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * form to create transaction
     * VueJS modal generates this form
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Transaction::class);
    }

    /**
     * store transactions
     * user should have create-transactions to be allowed
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
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
        // prepare transaction data
        $transactionData = [
            'currency' => $request->input('currency'),
            'amount' => $request->input('amount'),
            'comment' => $request->input('comment'),
        ];
        // create a transaction
        $transaction = $user->transactions()->create($transactionData);
        // if image is included on transaction creation time, then image should be uploaded and associated record will be created in db
        if ($request->has('image')) {
            // first upload image
            $image = $request->file('image');
            $imageName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageName);
            // create record for the uploaded image
            $imageData = [
                // imagable_type always remains App\Transaction
                'imagable_type' => 'App\Transaction',
                'imagable_id' => $transaction->id,
                'image_name' => $filePath
            ];
            $user->images()->create($imageData);
        }
        $transactionResult = Transaction::find($transaction);
        return response(['transaction' => TransactionResource::collection($transactionResult), 'message' => trans('translate.transaction_created')], 200);
    }

    /**
     * show a single transaction
     * user should have see-transactions permission to be allowed
     * VueJs shows this single transaction
     * @param Transaction $transaction
     * @return mixed
     * @throws AuthorizationException
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction =  Auth::user()->transactions->find($transaction);
        return response(['transaction' => new TransactionResource($transaction), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * edit form
     * users should have create-transactions permission to be allowed
     * VueJs generates this form
     * @param Transaction $transaction
     * @throws AuthorizationException
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
    }

    /**
     * update transactions
     * users should have create-transactions permission to be allowed
     * users can only update their own transaction records
     * @param Request $request
     * @param Transaction $transaction
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $user = Auth::user();
        request()->validate([
            'currency' => 'required',
            'amount' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'comment' => 'required'
        ]);
        $transactionData = [
            'currency' => $request->input('currency'),
            'amount' => $request->input('amount'),
            'comment' => $request->input('comment'),
        ];
        $transaction->update($transactionData);
        // if request has image for update then new image name will be generated and old image be deleted
        // if request dose not have image, then image name will not change
        if ($request->has('image')) {
            $oldImage = $transaction->images()
                ->where('imagable_type', 'App\Transaction')
                ->where('imagable_id', $transaction->id);
            $oldImageName = $oldImage->value('image_name');
            $image = $request->file('image');
            $imageNewName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageNewName);
            //delete the old image file and update respective record in the db
            $this->deleteOne('public', [$oldImageName]);
            $imageData = [
                // imagable_type always remains App\Transaction
                'imagable_type' => 'App\Transaction',
                'imagable_id' => $transaction->id,
                'image_name' => $filePath
            ];
            // update image record for the given user
            $oldImage->update($imageData);
        }
        return response(['transaction' => new TransactionResource($transaction),'message' => trans('translate.transaction_updated')], 200);
    }

    /**
     * delete transactions
     * @param Transaction $transaction
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $imageNameArray = $transaction->images()->where('imagable_id', $transaction->id)->pluck('image_name');
        DB::transaction(function () use ($transaction, $imageNameArray) {
            //delete the transaction's images
            $this->deleteOne('public', $imageNameArray);
            //delete the transaction image records
            $transaction->images()->delete();
            //delete the given transaction record
            $transaction->delete();
        }, 1);
        return response(['message' => trans('translate.deleted')], 200);
    }
}
