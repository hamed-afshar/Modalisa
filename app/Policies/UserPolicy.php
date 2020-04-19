<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    //users must be confirmed and not locked to access users function at first
    public function before(User $user)
    {
        if ($user->isLocked() || $user->isConfirmed() == false) {
            return false;
        }
    }

    //systemAdmin user is authorized for any function in user model
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    //only systemadmin can view a single user
    public function show(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    //only users with edit-profile permissions can update their profile
    public function update(User $authUser, User $targetUser)
    {
        if ($authUser->id = $targetUser->id) {
            if ($authUser->permissions()->contains('edit-profile')) {
                return true;
            }
        }
    }

    //even SystemAdmin can not delete a user
    public function destroy(User $user)
    {
        return false;
    }

}
