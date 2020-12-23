<?php

namespace App\Http\Controllers;

use App\Cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostController extends Controller
{
    /**
     * index costs
     */
    public function index()
    {
        $this->authorize('viewAny', Cost::class);
        return Auth::user()->costs;
    }

    /**
     * form to create cost
     * VueJs modal generates this form
     */
    public function create()
    {
        $this->authorize('create', Cost::class);
    }

    /**
     * store costs
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cost::class);
        dd('after cont');
    }
}
