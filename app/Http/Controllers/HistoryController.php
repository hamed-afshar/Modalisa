<?php

namespace App\Http\Controllers;

use App\History;
use App\Product;
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
}
