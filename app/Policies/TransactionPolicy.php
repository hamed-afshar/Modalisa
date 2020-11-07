<?php

namespace App\Policies;

use App\Transaction;
use App\Transactions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
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
     * Determine whether the user can view any transactions.
     * User should have make-payment permission to be allowed
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->checkPermission('make-payment')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the transactions.
     * User should have make-payment permission to be allowed
     * @param User $user
     * @param  \App\Transaction  $transaction
     * @return mixed
     */
    public function view(User $user, Transaction $transaction)
    {
        if($user->checkPermission('make-payment')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create transactions.
     * User should have make-payment permission to be allowed
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->checkPermission('make-payment')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the transactions.
     * User should have make-payment permission to be allowed
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function update(User $user, Transaction $transaction)
    {
        if($user->checkPermission('make-payment')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the transactions.
     *
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function delete(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can restore the transactions.
     *
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function restore(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the transactions.
     *
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function forceDelete(User $user, Transaction $transaction)
    {
        //
    }
}
