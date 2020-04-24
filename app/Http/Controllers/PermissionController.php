<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    //index permissions
    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Permission::class, 'permission');
    }

    public function index()
    {
        dd("index");
        $this->authorize('viewAny', auth()->user());
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    //form to create permissions
    public function create()
    {
        dd("create");
        $this->authorize('create', Permission::class);
        return view('permissions.create');
    }

    //store permissions
    public function store()
    {
        dd("store");
        $this->authorize('create', Permission::class);
        Permission::create(request()->validate([
            'name' => 'required',
            'label' => 'required'
        ]));
    }

    //show a single permission
    public function show(Permission $permission)
    {
        $this->authorize('view', auth()->user(), $permission);
        return view('permissions.show', compact('permission'));
    }

    //edit form
    public function edit(Permission $permission)
    {
        $this->authorize('update', auth()->user(), $permission);
        return view('permissions.edit', compact('permission'));
    }

    //update permission
    public function update(Permission $permission)
    {
        dd("update");
        $data = request()->validate([
           'name' => 'required'
        ]);
        $permission->update($data);
    }

    //delete a permission
    public function destroy(Permission $permission)
    {
        dd("delete");
        $permission->delete();
    }
}
