<?php

namespace App\Policies;

use App\Cost;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is locked or not confirmed first
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if($user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }
    /**
     * Determine whether the user can view any costs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->checkPermission('see-costs')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the cost.
     *
     * @param  \App\User  $user
     * @param  \App\Cost  $cost
     * @return mixed
     */
    public function view(User $user, Cost $cost)
    {
        if($user->checkPermission('see-costs') && $user->id == $cost->user->id) {
            return true;
        }

    }

    /**
     * Determine whether the user can create costs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->checkPermission('create-costs')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the cost.
     *
     * @param  \App\User  $user
     * @param  \App\Cost  $cost
     * @return mixed
     */
    public function update(User $user, Cost $cost)
    {
        if($user->checkPermission('create-costs'))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the cost.
     *
     * @param  \App\User  $user
     * @param  \App\Cost  $cost
     * @return mixed
     */
    public function delete(User $user, Cost $cost)
    {
        //
    }

    /**
     * Determine whether the user can restore the cost.
     *
     * @param  \App\User  $user
     * @param  \App\Cost  $cost
     * @return mixed
     */
    public function restore(User $user, Cost $cost)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the cost.
     *
     * @param  \App\User  $user
     * @param  \App\Cost  $cost
     * @return mixed
     */
    public function forceDelete(User $user, Cost $cost)
    {
        //
    }
}
