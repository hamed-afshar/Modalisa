<?php

namespace App\Http\Controllers;

use App\AccessProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    //index users
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return User::with(['subscription', 'role'])->get();
    }

    //systemadmin can see a single user
    public function show(User $user)
    {
        $this->authorize('view', User::class);
        return view('users.show', compact('user'));
    }

    //edit form is available to edit a user
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //update user's profile
    public function update(User $user)
    {
        $this->authorize('update', $user);
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'language' => 'required',
            'tel' => 'required',
            'country' => 'required',
            'communication_media' => 'required'
        ]);
        $user->update($data);
    }

    // delete a user
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        return redirect('access-denied');
    }

}
