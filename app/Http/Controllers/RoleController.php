<?php

namespace App\Http\Controllers;

use App\AccessProvider;
use App\Role;
use App\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //index roles
    public function index()
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'see-roles');
        if ($accessProvider->getPermission()) {
            $roles = Role::all();
            return view('roles.index', compact('roles'));
        } else {
            return ($accessProvider->accessDenied());
        }
    }

    //create form for role creation
    public function create()
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'create-roles');
        if ($accessProvider->getPermission()) {
            return view('roles.create');
        } else {
            return $accessProvider->accessDenied();
        }
    }

    //store role instance in db
    public function store()
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'create-roles');
        if ($accessProvider->getPermission()) {
            Role::create(request()->validate([
                'name' => 'required'
            ]));
        } else {
            return $accessProvider->accessDenied();
        }
    }

    //show a single role
    public function show(Role $role)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'see-roles');
        if ($accessProvider->getPermission()) {
            return view('roles.show', compact('role'));
        } else {
            return $accessProvider->accessDenied();
        }
    }

    //edit form
    public function edit(Role $role)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'edit-roles');
        if ($accessProvider->getPermission()) {
            return view('roles.edit', compact('role'));
        } else {
            return $accessProvider->accessDenied();
        }
    }

    //update a role
    public function update(Role $role)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'edit-roles');
        if ($accessProvider->getPermission()) {
            $data = request()->validate([
                'name' => 'required',
            ]);
            $role->update($data);
        } else {
            return $accessProvider->accessDenied();
        }

    }

    //delete a role
    public function destroy(Role $role)
    {
        $accessProvider = new AccessProvider(auth()->user()->id, 'delete-roles');
        if ($accessProvider->getPermission()) {
            $role->delete();
        } else {
            return $accessProvider->accessDenied();
        }
    }


}
