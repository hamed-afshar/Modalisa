<?php

namespace App\Http\Controllers;

use App\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    //index all roles assigned to users
    public function index()
    {
        $userRoles = UserRole::all();
        return view('user-roles.index', compact('userRoles'));
    }

    //create form to assign a role to a user
    public function create()
    {
        return view('user-roles.create');
    }

    //assign a role to a user
    public function store()
    {
        UserRole::create(request()->validate([
            'user_id' => 'required',
            'role_id' => 'required'
        ]));
        return redirect()->route('user-roles.index');

    }

    //show a single role assigned to a user
    public function show(UserRole $userRole)
    {
       return view('user-roles.show', compact('userRole'));
    }

    //form to edit a role assigned to a user
    public function edit(UserRole $userRole)
    {
        return view('user-roles.show', compact('userRole'));
    }

    //update a role assigned to a user
    public function update(UserRole $userRole)
    {
        $data = request()->validate([
            'user_id' => 'required',
            'role_id' => 'required'
        ]);
        $userRole->update($data);
        return redirect()->route('user-roles.show', $userRole);

    }

    //delete a role assigned to a user
    public function destroy(UserRole $userRole)
    {
        $userRole->delete();
        return redirect()->route('user-roles.index');
    }

}
