<?php

namespace App\Policies;

use App\Transaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is locked or not confirmed first
     * Also Determine to check any user just enable to modify its own transactions
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
        if($user->checkPermission('make-payment') && $user->id == $transaction->user->id) {
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
     * user also can only update its own records
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function update(User $user, Transaction $transaction)
    {
        if($user->checkPermission('make-payment') && $user->id == $transaction->user->id) {
            return $transaction->confirmed ? Response::deny('deny') : Response::allow();
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
        if($user->checkPermission('make-payment') && $user->id == $transaction->user->id) {
            return $transaction->confirmed ? Response::deny('deny') : Response::allow();
        }
    }

    /**
     * Determine whether user can confirm the transactions.
     * only SystemAdmin is able to confirm transactions
     * @param  User $user
     * @param  Transaction $transaction
     * @return mixed
     */
    public function confirm(User $user, Transaction $transaction)
    {
        if($user->isAdmin()) {
            return true;
        }
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
