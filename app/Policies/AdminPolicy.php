<?php

namespace App\Policies;

use App\Admin;
use App\Cost;
use App\User;
use http\Env\Request;
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

    /**
     * Determine whether admin can create a cost for the given user
     * @param User $user
     * @return bool
     */
    public function createCost(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * Determine whether admin can update a cost for the given user
     * @param User $user
     * @return bool
     */
    public function updateCost(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * Determine whether admin can delete a cost for the given user
     * @param User $user
     * @return bool
     */
    public function deleteCost(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * Determine whether admin can create kargo
     */
    public function createKargo(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * Determine whether admin can index all kargos
     * @param User $user
     * @return bool
     */
    public function indexKargos(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * Determine whether admin can view a single cost
     * @param User $user
     * @return bool
     */
    public function indexSingleKargo(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * Determine whether the user can confirm the kargo
     * @param User $user
     * @return bool
     */
    public function confirmKargo(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * determine whether admin can update a kargo
     * @param User $user
     * @return bool
     */
    public function updateKargo(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * determine whether admin can delete a kargo
     * @param User $user
     * @return bool
     */
    public function deleteKargo(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * determine whether admin can confirm Transaction
     * @param User $user
     * @return bool
     */
    public function confirmTransaction(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * determine whether admin can index orders
     * @param User $user
     * @return bool
     */
    public function indexOrder(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }

    /**
     * determine whether admin can index a single order
     * @param User $user
     * @return bool
     */
    public function indexSingleOrder(User $user)
    {
        if($user->checkPrivilegeRole()) {
            return true;
        }
    }


}
