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
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    //create form for role creation
    public function create()
    {
        return view('roles.create');
    }

    //store role instance in db
    public function store()
    {
        Role::create(request()->validate([
            'name' => 'required'
        ]));
        return redirect()->route('roles.index');
    }

    //show a single role
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    //edit form
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    //update a role
    public function update(Role $role)
    {
        $data = request()->validate([
            'name' => 'required',
        ]);
        $role->update($data);
        return redirect()->route('roles.show', $role);
    }

    //delete a role
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index');
    }


}
