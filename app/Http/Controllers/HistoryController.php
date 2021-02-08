<?php

namespace App\Http\Controllers;

use App\History;
use App\Product;
use App\Status;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * index histories
     * users should have see-histories permission to be allowed
     * returns all histories for the given product
     * users can only index their own records
     * @param Product $product
     * @return mixed
     * @throws AuthorizationException
     */
    public function index(Product $product)
    {
        $this->authorize('viewAny', History::class);
        $owner = $product->order->user;
        //if products belongs to the current authenticated user then return all related histories, otherwise returns null
        if($owner->id == Auth::user()->id) {
            return $product->histories;
        } else {
            return null;
        }
    }

    /**
     * create history
     * BuyerAdmin and users with privilege permissions are allowed
     * @param Request $request
     * @param Product $product
     * @param Status $status
     * @throws AuthorizationException
     */
    public function store(Request $request, Product $product, Status $status)
    {
        $this->authorize('create', History::class);
        $request->validate([
            'product_id' => 'required',
            'status_id' => 'required'
        ]);
        $historyData = [
            'product_id' => $request->input('product_id'),
            'status_id' => $request->input('status_id')
        ];
        History::create($historyData);
    }

    /**
     * delete history
     * BuyerAdmin and users with privilege permissions are allowed
     * @param History $history
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(History $history)
    {
        $this->authorize('delete', $history);
        $history->delete();
    }
}
