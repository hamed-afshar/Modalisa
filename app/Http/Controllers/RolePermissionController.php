<?php

namespace App\Http\Controllers;

use App\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    //index
    public function index()
    {
        $rolePermissions = RolePermission::all();
        return view('role-permissions.index', compact('rolePermissions'));
    }

    //form to create a role assigned to permission
    public function create()
    {
        return view('role-permissions.create');
    }

    //store
    public function store()
    {
        RolePermission::create(request()->validate([
            'role_id' => 'required',
            'permission_id' => 'required'
        ]));
        return redirect()->route('role-permissions.index');
    }

    //show a single permission assigned to role
    public function show(RolePermission $rolePermission)
    {
        return view('role-permissions.show', compact('rolePermission'));
    }

    //edit form to update a permission assigned to role
    public function edit(RolePermission $rolePermission)
    {
        return view('role-permissions.edit', compact('rolePermission'));
    }

    //update a permission assigned to role
    public function update(RolePermission $rolePermission)
    {
        $data = request()->validate([
           'role_id' => 'required',
           'permission_id' => 'required'
        ]);
        $rolePermission->update($data);
        return redirect()->route('role-permissions.show', $rolePermission);
    }

    //delete a permission assigned to role
    public function destroy(RolePermission $rolePermission)
    {
        $rolePermission->delete();
        return redirect()->route('role-permissions.index');
    }
}
