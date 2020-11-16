<?php

namespace App\Http\Controllers;

use App\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * index histories
     */
    public function index()
    {
        $this->authorize('viewAny', History::class);
    }
}
