<?php

namespace App\Http\Controllers;

use App\AccessProvider;
use App\Permission;
use App\Role;
use App\RolePermission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //index roles
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        return Role::all();
    }

    //create form for role creation

    public function create()
    {
        $this->authorize('create', Role::class);
        return view('roles.create');
    }

    //store role instance in db
    public function store()
    {
        $this->authorize('create', Role::class);
        Role::create(request()->validate([
            'name' => 'required',
            'label' => 'required'
        ]));
        return redirect()->route('roles.index');
    }

    //show a single role
    public function show(Role $role)
    {
        $this->authorize('view', $role);
        return view('roles.show', compact('role'));
    }

    //edit form
    public function edit(Role $role)
    {
        $this->authorize('update', $role);
        return view('roles.edit', compact('role'));
    }

    //update a role
    public function update(Role $role)
    {
        $this->authorize('update', $role);
        $data = request()->validate([
            'name' => 'required',
        ]);
        $role->update($data);
        return redirect()->route('roles.show', $role);
    }

    //delete a role
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();
        return redirect()->route('roles.index');
    }


}
