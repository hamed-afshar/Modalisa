<?php

namespace App\Policies;

use App\Kargo;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class KargoPolicy
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
     * Determine whether the user can view any kargos.
     * users should have see-kargos permission to be allowed
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->checkPermission('see-kargos')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the kargo.
     * users should have see-kargos permission to be allowed
     * @param  \App\User  $user
     * @param  \App\Kargo  $kargo
     * @return mixed
     */
    public function view(User $user, Kargo $kargo)
    {
        if($user->checkPermission('see-kargos') && $user->id == $kargo->user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can create kargos.
     * users should have create-kargos permission to be allowed
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->checkPermission('create-kargos')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the kargo.
     *
     * @param  \App\User  $user
     * @param  \App\Kargo  $kargo
     * @return mixed
     */
    public function update(User $user, Kargo $kargo)
    {
        if($user->checkPermission('create-kargos') && $user->id == $kargo->user->id)
        {
            return $kargo->confirmed ? Response::deny('deny') : Response::allow();
        }
    }

    /**
     * Determine whether the user can delete the kargo.
     *
     * @param  \App\User  $user
     * @param  \App\Kargo  $kargo
     * @return mixed
     */
    public function delete(User $user, Kargo $kargo)
    {
        //
    }


    /**
     * Determine whether the user can restore the kargo.
     *
     * @param  \App\User  $user
     * @param  \App\Kargo  $kargo
     * @return mixed
     */
    public function restore(User $user, Kargo $kargo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the kargo.
     *
     * @param  \App\User  $user
     * @param  \App\Kargo  $kargo
     * @return mixed
     */
    public function forceDelete(User $user, Kargo $kargo)
    {
        //
    }
}
