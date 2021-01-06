<?php

namespace App\Http\Controllers;

use App\History;
use App\Product;
use App\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * index histories
     * returns all histories for the given product
     * users can only index their own records
     * @param Product $product
     * @return mixed
     * @throws AuthorizationException
     */
    public function index(Product $product)
    {
        $this->authorize('viewAny', History::class);
        return $product->histories;
    }

    /**
     * create history
     * users should have create-histories permission to be allowef
     * @param Product $product
     * @param Status $status
     * @throws AuthorizationException
     */
    public function store(Product $product, Status $status)
    {
        $this->authorize('create', History::class);
        $product->changeHistory($status);
    }

    /**
     * delete history
     */
    public function destroy(History $history)
    {
        $this->authorize('delete', $history);
        $history->delete();
    }
}
