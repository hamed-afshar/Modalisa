<?php

namespace App\Policies;

use App\Permission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if(!($user->isAdmin()) || $user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }

    /**
     * Determine whether the user can view any permissions.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function view(User $user, Permission $permission)
    {
        return true;
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function update(User $user, Permission $permission)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function delete(User $user, Permission $permission)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function restore(User $user, Permission $permission)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function forceDelete(User $user, Permission $permission)
    {
        //
    }
}
