<?php

namespace App\Policies;

use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is locked or not confirmed first
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }

    /**
     * Determine whether the user can view any orders.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->checkPermission('see-orders')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        if($user->checkPermission('see-orders') && $order->user->id == $user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->checkPermission('create-orders')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        if($user->checkPermission('create-orders') && $order->user->id == $user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        if($user->checkPermission('see-orders') && $order->user->id == $user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function restore(User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function forceDelete(User $user, Order $order)
    {
        //
    }
}
