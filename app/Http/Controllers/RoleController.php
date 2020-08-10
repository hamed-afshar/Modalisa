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
    /*
     * index roles
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        return Role::all();
    }

    /*
     * form to create roles
     * vue-js modal generates this form
     */
    public function create()
    {
        $this->authorize('create', Role::class);
    }

    /*
     * store roles
     */
    public function store()
    {
        $this->authorize('create', Role::class);
        Role::create(request()->validate([
            'name' => 'required',
            'label' => 'required'
        ]));
    }

    /*
     * show a single role
     * vue-js shows single role
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);
    }

    /*
     * edit form
     * vue-js generates this form
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);
    }

    /*
     * update roles
     */
    public function update(Role $role)
    {
        $this->authorize('update', $role);
        $data = request()->validate([
            'name' => 'required',
            'label' => 'required'
        ]);
        $role->update($data);
    }

    /*
     * delete roles
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();
    }

    /*
     * get all permission associated to the role
     */
    public function permissions(Role $role)
    {
        $this->authorize('view', $role );
        return $role->permissions;
    }

    /*
     * assign permission to role
     */
    public function assignPermission(Role $role)
    {
        $this->authorize('update', $role);
        return $role->permissions;
    }

}
