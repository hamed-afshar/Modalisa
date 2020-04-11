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
        $users = User::all();
        return view('users.index', compact('users'));
    }

    //form to create a user
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
        return redirect()->route('pending');
    }

    //systemadmin can see a single user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //edit form is available to edit a user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    //update a user information
    public function update(User $user)
    {
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
        return redirect('access-denied');
    }

//show pending for confirmation page
    public function pending()
    {
        return view('/others/pending-for-confirmation');
    }
}
