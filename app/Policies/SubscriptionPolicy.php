<?php

namespace App\Policies;

use App\Subscription;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if(!($user->isAdmin()) || $user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }
    /**
     * Determine whether the user can view any subscriptions.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the subscription.
     *
     * @param User $user
     * @param Subscription $subscription
     * @return mixed
     */
    public function view(User $user, Subscription $subscription)
    {
        return true;
    }

    /**
     * Determine whether the user can create subscriptions.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the subscription.
     *
     * @param User $user
     * @param Subscription $subscription
     * @return mixed
     */
    public function update(User $user, Subscription $subscription)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the subscription.
     *
     * @param User $user
     * @param Subscription $subscription
     * @return mixed
     */
    public function delete(User $user, Subscription $subscription)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the subscription.
     *
     * @param User $user
     * @param Subscription $subscription
     * @return mixed
     */
    public function restore(User $user, Subscription $subscription)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the subscription.
     *
     * @param User $user
     * @param Subscription $subscription
     * @return mixed
     */
    public function forceDelete(User $user, Subscription $subscription)
    {
        //
    }
}
