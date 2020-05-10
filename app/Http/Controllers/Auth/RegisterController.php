<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => 'required|string|min:5|max:50',
            'email' => 'required|unique:users|email:rfc,dns',
            'password' => 'required|confirmed|min:8',
            'language' => 'required',
            'tel' => 'required|digits:12',
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

    //show pending for confirmation page
    public function pending()
    {
        return view('/others/pending-for-confirmation');
    }
}
