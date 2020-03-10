<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //index permissions
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    //form to create permissions
    public function create()
    {
        return view('permissions.create');
    }

    //store permissions
    public function store()
    {
        Permission::create(request()->validate([
            'name' => 'required'
        ]));
    }

    //show a single permission
    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }
}
