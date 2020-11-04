<?php

namespace App\Policies;

use App\Transactions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if($user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }

    /**
     * Determine whether the user can view any transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the transactions.
     *
     * @param  \App\User  $user
     * @param  \App\Transactions  $transactions
     * @return mixed
     */
    public function view(User $user, Transactions $transactions)
    {
        //
    }

    /**
     * Determine whether the user can create transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the transactions.
     *
     * @param  \App\User  $user
     * @param  \App\Transactions  $transactions
     * @return mixed
     */
    public function update(User $user, Transactions $transactions)
    {
        //
    }

    /**
     * Determine whether the user can delete the transactions.
     *
     * @param  \App\User  $user
     * @param  \App\Transactions  $transactions
     * @return mixed
     */
    public function delete(User $user, Transactions $transactions)
    {
        //
    }

    /**
     * Determine whether the user can restore the transactions.
     *
     * @param  \App\User  $user
     * @param  \App\Transactions  $transactions
     * @return mixed
     */
    public function restore(User $user, Transactions $transactions)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the transactions.
     *
     * @param  \App\User  $user
     * @param  \App\Transactions  $transactions
     * @return mixed
     */
    public function forceDelete(User $user, Transactions $transactions)
    {
        //
    }
}
