<?php

namespace App\Policies;

use App\Customer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is locked or not confirmed first
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if($user->isLocked() || !($user->isConfirmed()))
        {
            return false;
        }
    }

    /**
     * Determine whether the user can view any customers.
     * User should only be able to see its own customers.
     * User should have see-customers permission to be allowed
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->checkPermission('see-customers'))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can view the customer.
     * User should only be able to see its own customers.
     * User should have see-customers permission to be allowed
     * @param  \App\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function view(User $user, Customer $customer)
    {
        if($user->checkPermission('see-customers') && $user->id == $customer->user->id)
        {
            return true;
        }
    }

    /**
     * Determine whether the user can create customers.
     * User should have create-customer permission to be allowed
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->checkPermission('create-customers'))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can update the customer.
     * User should have create-customer permission to be allowed
     * User should only be able to update its own customers.
     * @param  \App\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function update(User $user, Customer $customer)
    {
        if($user->checkPermission('create-customers') && $user->id == $customer->user->id)
        {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the customer.
     *
     * @param  \App\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function delete(User $user, Customer $customer)
    {
        if($user->checkPermission('delete-customers') && $user->id == $customer->user->id)
        {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the customer.
     *
     * @param  \App\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function restore(User $user, Customer $customer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the customer.
     *
     * @param  \App\User  $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function forceDelete(User $user, Customer $customer)
    {
        //
    }
}
