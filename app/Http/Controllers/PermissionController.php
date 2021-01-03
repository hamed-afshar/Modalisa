<?php

namespace App\Http\Controllers;

use App\Permission;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * index permissions
     * only system admin can see permissions
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        return $permissions = Permission::all();
    }

    /**
     * Status create form
     * VueJs Modal
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Permission::class);

    }

    /**
     * store permission
     * @param Request $request
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);
        $request->validate([
            'name' => 'required',
            'label' => 'required'
        ]);
        $permissionData = [
            'name' => $request->input('name'),
            'label' => $request->input('label')
        ];
        Permission::create($permissionData);
    }

    /**
     * show a single permission
     * VueJs shows this single permission
     * @param Permission $permission
     * @return Permission
     * @throws AuthorizationException
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return $permission;
    }

    /**
     * Permission update form
     * VueJs Modal
     * @param Permission $permission
     * @throws AuthorizationException
     */
    public function edit(Permission $permission)
    {
        $this->authorize('update', Permission::class);
    }

    /**
     * update permissions
     * @param Permission $permission
     * @throws AuthorizationException
     */
    public function update(Permission $permission)
    {
        $this->authorize('update', $permission);
        $data = request()->validate([
            'name' => 'required',
            'label' => 'required'
        ]);
        $permission->update($data);
    }

    /**
     * delete permissions
     * @param Permission $permission
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        $permission->delete();
    }
}
