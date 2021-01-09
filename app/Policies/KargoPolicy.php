<?php

namespace App\Policies;

use App\Kargo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KargoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any kargos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the kargo.
     *
     * @param  \App\User  $user
     * @param  \App\Kargo  $kargo
     * @return mixed
     */
    public function view(User $user, Kargo $kargo)
    {
        //
    }

    /**
     * Determine whether the user can create kargos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
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
        //
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
