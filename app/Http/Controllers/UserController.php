<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('users.create');
    }

    // function for retailers registration
    public function register() {
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
            'roles_id' =>request('roles_id'),
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
    public function showPendingForConfirmation() {
        return view('others.pending-for-confirmation');
    }

    //only SystemAdmin users can view the all users list
    public function getAllUserList() {
        if (auth()->user()->getAccessLevel() != "SystemAdmin") {
            return auth()->user()->showAccessDenied();
        } else {
            $allUsers = User::all();
            return view('users.all-users', compact('allUsers'));
        }
    }

    //SystemAdmin can confirm user or change access level
    public function update(User $user) {
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
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        return redirect('access-denied');
    }

    //redirect to access denied page
    public function showAccessDenied() {
        return view('others.access-denied');
    }

    //show users profile to SystemAdmin
    public function showUserProfile(User $user) {
        if (auth()->user()->getAccessLevel() != "SystemAdmin") {
            return auth()->user()->showAccessDenied();
        } else {
            return view('users.user-profile', compact('user'));
        }
    }

}
