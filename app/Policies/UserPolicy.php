<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is locked or not confirmed first
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isLocked() || $user->isConfirmed() == false) {
            return false;
        }
    }

    /**
     * Determine whether the SystemAdmin can view any models.
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the SystemAdmin can view the model.
     *
     * @param User $user
     * @return mixed
     */
    public function view(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the SystemAdmin can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the SystemAdmin can update user's information.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     * Users can not be deleted
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether user can update it's profile
     * users can only update their own profiles
     * @param User $user
     * @param User $modal
     * @return bool
     */
    public function profile(User $user, User $modal)
    {
        //check to see if user is requesting to update its own record
        if($user->id == $modal->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
