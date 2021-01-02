<?php

namespace App\Policies;

use App\Admin;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is locked or not confirmed first
     * @param User $user
     * @return false
     */
    public function before(User $user)
    {
        if($user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }

    /**
     * Determine whether admin can view all cost for the given user
     * @param User $user
     * @return bool
     */
    public function indexCosts(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * Determine whether admin can view a single cost for the given user
     * @param User $user
     * @return bool
     */
    public function indexSingleCost(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

}
