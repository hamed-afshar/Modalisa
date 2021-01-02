<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Cost;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * index costs for the given user
     * super privilege users are able to see all costs created for any retailer
     * @param User $user
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function indexCosts(User $user)
    {
        $this->authorize('indexCosts', Admin::class);
        return $user->costs;
    }

    /**
     * show a single cost for the given user
     * super privilege users are able to see a single cost for a specific user
     * @param User $user
     * @param Cost $cost
     */
    public function showCost(User $user, Cost $cost)
    {
        $this->authorize('indexSingleCost', Admin::class);
        return $user->costs;
    }
}
