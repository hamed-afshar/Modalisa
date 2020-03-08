<?php

namespace App\Http\Controllers;

use App\AccessProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    //index users
    public function index()
    {
      $accessProvider = new AccessProvider(auth()->user()->id, 'see-users');
        if ($accessProvider->getPermission()) {
            $users = User::all();
            return view('users.index', compact('users'));
        } else {
            return $accessProvider->accessDenied();
        }
    }

    //create user form
    public function create()
    {
        return view('users.create');
    }

    // store users
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'language' => 'required',
            'tel' => 'required',
            'country' => 'required',
            'communication_media' => 'required'
        ]);
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make($data['password']),
            'last_ip' => request()->ip(),
            'language' => request('language'),
            'tel' => request('tel'),
            'country' => request('country'),
            'communication_media' => request('communication_media')
        ]);
        return redirect('/pending-for-confirmation');
    }

    //function to show pending-for-confirmation-page
    public function showPendingForConfirmation()
    {
        return view('others.pending-for-confirmation');
    }

    //show a single user
    public function show(User $user)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'see-users');
        if ($accessProvider->getPermission()) {
            return view('users.show', compact('user'));
        } else {
            return $accessProvider->accessDenied();
        }
    }

    //user edit form
    public function edit(User $user)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'edit-profile');
        if ($accessProvider->getPermission()) {
            return view('users.edit', compact('user'));
        } else {
            return $accessProvider->accessDenied();
        }
    }


    //Edit user's profile
    public function update(User $user)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'edit-profile');
        if ($accessProvider->getPermission()) {
            $data = request()->validate([
                'email' => 'required',
                'password' => 'required',
                'language' => 'required',
                'tel' => 'required',
                'country' => 'required',
                'communication_media' => 'required'
            ]);
            $user->update($data);
        } else {
            $accessProvider->accessDenied();
        }
    }

// Remove user from db
    public function destroy(User $user)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'delete-user');
        if ($accessProvider->getPermission()) {
            $user->delete();
        } else {
            return $accessProvider->accessDenied();
        }
    }


}
