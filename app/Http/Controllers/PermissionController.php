<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    /*
     * index permissions
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        return $permissions = Permission::all();
    }

    /*
     * form to create permissions
     * VueJs modal generates this form
    */
    public function create()
    {
        $this->authorize('create', Permission::class);
    }

    /*
     * store permission
     */
    public function store()
    {
        $this->authorize('create', Permission::class);
        Permission::create(request()->validate([
            'name' => 'required',
            'label' => 'required'
        ]));
    }

    /*
     * show a single permission
     * VueJs shows this single permission
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
    }

    /*
     * edit form
     * VueJs generates this form
     */
    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);
    }

    /*
     * update permissions
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

    /*
     * delete permissions
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        $permission->delete();
    }
}
