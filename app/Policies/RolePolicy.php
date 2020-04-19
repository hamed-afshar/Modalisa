<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    //to access role management system user must be SystemAdmin, Not Locked and confirmed at first
//    public function before(User $user)
//    {
//        if ($user->isConfirmed() && $user->isLocked() == false) {
//            return false;
//        }
//    }

    public function index(User $user)
    {
        if($user->isAdmin())
        {
            return true;
        }

    }
}
