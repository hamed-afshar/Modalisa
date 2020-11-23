<?php

namespace App\Http\Controllers;

use App\History;
use App\Product;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * index histories
     */
    public function index(Product $product)
    {
        $this->authorize('viewAny', History::class);
        return $product->histories;
    }

    /**
     * create history
     */
    public function store(Product $product, Status $status)
    {
        $this->authorize('create', History::class);
        $product->changeHistory($status);
    }
}
