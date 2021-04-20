<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $roles = Role::all();
        return response(['roles' => RoleResource::collection($roles), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * store roles
     * only SystemAdmin is able to create roles
     * @param Request $request
     * @return Application|ResponseFactory|Response
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
        $role = Role::create($roleData);
        return response(['roles' => new RoleResource($role), 'message' => trans('translate.retrieved')], 200);
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
        return response(['roles' => new RoleResource($role->with('permissions')->where('id', '=', $role->id)->get()), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * update roles
     * only SystemAdmin can update roles
     * @param Role $role
     * @return Application|ResponseFactory|Response
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
        return response(['roles' => new RoleResource($role), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * delete roles
     * only SystemAdmin can delete roles
     * @param Role $role
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();
        return response(['message' => trans('translate.deleted'), 200]);
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
        $permissions = $role->permissions;
        return response(['permissions' => RoleResource::collection($permissions), 'message' => trans('translate.retrieved')], 200);
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
        $permissions =  $role->permissions;
        return response(['permissions' => RoleResource::collection($permissions), 'message' => trans('translate.retrieved')], 200);

    }

    /**
     * change user's role
     * @param Role $role
     * @param User $user
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function changeRole(Role $role, User $user)
    {
        $this->authorize('update', $role);
        $role->changeRole($user);
        return response(['roles' => new RoleResource($role), 'message' => trans('translate.retrieved')], 200);
    }
}
