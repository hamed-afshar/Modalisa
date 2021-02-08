<?php

namespace App\Policies;

use App\History;
use App\Product;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HistoryPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if($user->isLocked() || !($user->isConfirmed()))
        {
            return false;
        }
    }

    /**
     * Determine whether the user can view any histories.
     * user should have see-histories permission to be allowed
     * users can only see their own records
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->checkPermission('see-histories') )
        {
            return true;
        }
    }

    /**
     * Determine whether the user can view the history.
     * user should have see-histories permission to be allowed
     * @param User $user
     * @param History $history
     * @return mixed
     */
    public function view(User $user, History $history)
    {
        //
    }

    /**
     * Determine whether the user can create histories.
     * Only BuyerAdmin and other users with privilege permissions are allowed
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->checkPrivilegeRole())
        {
            return true;
        }
    }

    /**
     * Determine whether the user can update the history.
     *
     * @param User $user
     * @param History $history
     * @return mixed
     */
    public function update(User $user, History $history)
    {
        //
    }

    /**
     * Determine whether the user can delete the history.
     * Only BuyerAdmin and other users with privilege permissions are allowed
     * @param User $user
     * @param History $history
     * @return mixed
     */
    public function delete(User $user, History $history)
    {
        if($user->checkPrivilegeRole())
        {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the history.
     *
     * @param User $user
     * @param History $history
     * @return mixed
     */
    public function restore(User $user, History $history)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the history.
     *
     * @param User $user
     * @param History $history
     * @return mixed
     */
    public function forceDelete(User $user, History $history)
    {
        //
    }
}
