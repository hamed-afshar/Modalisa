<?php

namespace App\Http\Controllers;

use App\AccessProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class , 'user');
    }

    //index users
    public function index()
    {
        $this->authorize('viewAny', auth()->user());
        $users = User::all();
        return view('users.index', compact('users'));
    }

    //systemadmin can see a single user
    public function show(User $user)
    {
        $this->authorize('view', auth()->user());
        return view('users.show', compact('user'));
    }

    //edit form is available to edit a user
    public function edit(User $user)
    {
        $this->authorize('update', $user, auth()->user());
        return view('users.edit', compact('user'));
    }

    //update user's profile
    public function update(User $user)
    {
        $this->authorize('update', $user, auth()->user());
        $data = request()->validate([
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
        $this->authorize('destroy', auth()->user());
        return redirect('access-denied');
    }
}
