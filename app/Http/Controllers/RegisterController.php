<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller {

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
            'email' => request('email'),
            'email_verified_at' => request('email_verified_at'),
            'password' => request('password'),
            'remember_token' => request('remember_token'),
            'access_level' => request('access_level'),
            'last_login' => request('last_login'),
            'lock' => request('lock'),
            'last_ip' => request('last_ip'),
            'language' => request('language'),
            'tel' => request('tel'),
            'country' => request('country'),
            'communication_media' => request('communication_media')
        ]);
    }
    
    //only SystemAdmin users can view the all users list
    public function getAllUserList() {
        $access_level = DB::table('users')->where('id', auth()->id())->value('access_level');
        if($access_level != "SystemAdmin") {  
            return redirect('/access-denied');
        }
        $allUsers = User::all();
        return view('users.all-users', compact('allUsers'));
    }
    
    //SystemAdmin can confirm user or change access level
    public function update(User $user) {
        
        $data = request()->validate([
            'confirmed' => 'required',
            'access_level' => 'required',
            'lock' => 'required'
        ]);
        $user->update($data);
    }
    
    //show access denied
    public function showAccessDenied() {
        return view('others.access-denied');
    }
    
}
