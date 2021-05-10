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
     * User should have see-transactions permission to be allowed
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->checkPermission('see-transactions')) {
            return true;
        }
    }

    /**
     * Determine whether the users can view all of their transactions.
     * User should have see-transactions permission to be allowed
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function view(User $user, Transaction $transaction)
    {
        if($user->checkPermission('see-transactions') && $user->id == $transaction->user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can create transactions.
     * User should have create-transaction permission to be allowed
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->checkPermission('create-transactions')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the transactions.
     * User should have create-transactions permission to be allowed
     * users are only able to update their own records
     * users are not allowed to update confirmed transactions
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function update(User $user, Transaction $transaction)
    {
        if($user->checkPermission('create-transactions') && $user->id == $transaction->user->id) {
            return $transaction->confirmed ? Response::deny(trans('translate.can_not_update_transaction')) : Response::allow();
        }
    }

    /**
     * Determine whether the user can delete the transactions.
     * users should have delete-transactions permission to be allowed
     * users are not allowed to delete confirmed transactions
     * users can only delete their own transactions
     * @param User $user
     * @param Transaction $transaction
     * @return mixed
     */
    public function delete(User $user, Transaction $transaction)
    {
        if($user->checkPermission('delete-transactions') && $user->id == $transaction->user->id) {
            return $transaction->confirmed ? Response::deny(trans('translate.can_not_delete_transaction')) : Response::allow();
        }
    }

    /**
     * Determine whether user can confirm transactions.
     * only SystemAdmin is able to confirm transactions
     * @param  User $user
     * @return mixed
     */
    public function confirm(User $user)
    {
        if($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore transactions.
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
