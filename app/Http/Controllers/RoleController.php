<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    /**
     * index roles
     * only SystemAdmin is able to see roles
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        return Role::all();
    }

    /**
     * form to create roles
     * vue-js modal generates this form
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Role::class);
    }

    /**
     * store roles
     * only SystemAdmin is able to create roles
     * @param Request $request
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        $request->validate([
            'name' => 'required',
            'label' => 'required'
        ]);
        $roleData = [
            'name' => $request->input('name'),
            'label' => $request->input('label')
        ];
        Role::create($roleData);
    }

    /**
     * show a single role with all assigned permissions
     * vue-js shows single role
     * @param Role $role
     * @return mixed
     * @throws AuthorizationException
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);
        return $role->permissions;
    }

    /**
     * edit form
     * vue-js generates this form
     * @param Role $role
     * @throws AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);
    }

    /**
     * update roles
     * only SystemAdmin can update role
     * @param Role $role
     * @throws AuthorizationException
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

    /**
     * delete roles
     * only SystemAdmin can delete roles
     * @param Role $role
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();
    }

    /**
     * assign permissions to the given role
     * only SystemAdmin can assign permission to roles
     * @param Role $role
     * @param Permission $permission
     * @return mixed
     * @throws AuthorizationException
     */
    public function allowToPermission(Role $role, Permission $permission)
    {
        $this->authorize('update', $role);
        $role->allowTo($permission);
        return $role->permissions;
    }

    /**
     * disallow roles for permissions
     * @param Role $role
     * @param Permission $permission
     * @return mixed
     * @throws AuthorizationException
     */
    public function disallowToPermission(Role $role, Permission $permission)
    {
        $this->authorize('update', $role);
        $role->disAllowTo($permission);
        return $role->permissions;
    }

    /**
     * change user's role
     * @param Role $role
     * @param User $user
     * @throws AuthorizationException
     */
    public function changeRole(Role $role, User $user)
    {
        $this->authorize('update', $role);
        $role->changeRole($user);
    }
}
