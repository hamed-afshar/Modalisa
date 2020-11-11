<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    /*
     * index users
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return User::with(['subscription', 'role'])->get();
    }

    /*
     * SystemAdmin can see a single user
     */
    public function show(User $user)
    {
        $this->authorize('view', User::class);
        return $user;
    }

    /*
     * edit form is available to edit a user
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
    }

    /*
     * update user's information by SystemAdmin
     */
    public function update(User $user)
    {
        $this->authorize('update', $user);
        $data = request()->all();
        $user->update($data);
    }

    /*
     * delete user
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
    }

    /*
     * edit profile by user
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
}
