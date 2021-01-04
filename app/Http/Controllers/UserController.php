<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{

    /**
     * index users
     * only SystemAdmin can see users
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        //return user with subscriptions and roles
        return User::with(['subscription', 'role'])->get();
    }

    /**
     * SystemAdmin can see a single user
     * only SystemAdmin can view a single user
     * @param User $user
     * @return User
     * @throws AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('view', User::class);
        return $user;
    }

    /**
     * form is available to edit a user
     * @param User $user
     * @throws AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
    }

    /**
     * update user's information by SystemAdmin
     * @param User $user
     * @throws AuthorizationException
     */
    public function update(User $user)
    {
        $this->authorize('update', $user);
        $data = request()->all();
        $user->update($data);
    }

    /**
     * users can update their profile
     * users can not update other user's profile
     * @param User $user
     * @throws AuthorizationException
     */
    public function editProfile(User $user)
    {
        $this->authorize('profile', $user);
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'language' => 'required',
            'tel' => 'required',
            'country' => 'required',
            'communication_media' => 'required'
        ]);
        $user->update($data);
    }

    /**
     * delete user
     * users can not be deleted from the system
     * @param User $user
     * @throws AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
    }


}
