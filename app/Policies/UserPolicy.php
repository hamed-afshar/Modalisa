<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    //systemAdmin user is authorized for any function in user model
    public function index(User $user)
    {
        if($user->roles()->pluck('name')->contains('SystemAdmin'))
        {
            return true;
        }
    }

}
