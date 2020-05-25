<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard based on assigned role
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('dashboards.system-admin');
        }
        return view('home');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function security_center()
    {
        $this->authorize('viewAny', Role::class);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('dashboards.security-center', compact('roles', 'permissions'));
    }

    public function user_center()
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();
        return view('dashboards.user-center', compact('users'));
    }
}
