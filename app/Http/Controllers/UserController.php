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
        $accessProvider = new AccessProvider(auth()->user()->id, 'edit-users');
        if ($accessProvider->getPermission()) {
            return view('users.edit', compact('user'));
        } else {
            return $accessProvider->accessDenied();
        }
    }


    //SystemAdmin can confirm user or change access level
    public function update(User $user)
    {
        if (auth()->user()->getAccessLevel() != "SystemAdmin") {
            return auth()->user()->showAccessDenied();
        } else {
            $data = request()->validate([
                'confirmed' => 'required',
                'access_level' => 'required',
                'lock' => 'required'
            ]);
            $user->update($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return redirect('access-denied');
    }


}
