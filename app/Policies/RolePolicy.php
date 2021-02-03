<?php

namespace App\Policies;

use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if(!($user->isAdmin()) || $user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }
    /**
     * Determine whether the user can view any roles.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
       return true;
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
//
        return true;
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function restore(User $user, Role $role)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function forceDelete(User $user, Role $role)
    {
        //
    }

}
