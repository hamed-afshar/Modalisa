<?php

namespace App\Http\Controllers;

use App\AccessProvider;
use App\Role;
use App\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //store role instance in db
    public function store()
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'create-role');
        if ($accessProvider->getPermission()) {
            Role::create(request([
                'name' => 'name'
            ]));
        } else {
            return $accessProvider->accessDenied();
        }

    }

    //index roles
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

}
