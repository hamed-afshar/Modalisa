<?php

namespace App\Policies;

use App\Status;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is BuyerAdmin or not
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if (!($user->isAdmin()) || $user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }

    /**
     * Determine whether the user can view any statuses.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the status.
     *
     * @param User $user
     * @return mixed
     */
    public function view(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create statuses.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the status.
     *
     * @param User $user
     * @param Status $status
     * @return mixed
     */
    public function update(User $user, Status $status)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the status.
     *
     * @param User $user
     * @param Status $status
     * @return mixed
     */
    public function delete(User $user, Status $status)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the status.
     *
     * @param User $user
     * @param Status $status
     * @return mixed
     */
    public function restore(User $user, Status $status)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the status.
     *
     * @param User $user
     * @param Status $status
     * @return mixed
     */
    public function forceDelete(User $user, Status $status)
    {
        //
    }
}
