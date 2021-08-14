<?php

namespace App\Http\Controllers\API;

use App\Exceptions\PermissionExist;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Permission;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Exception\PcreException;

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
        $permissions = Permission::all();
        return response(['permissions' => PermissionResource::collection($permissions), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * store permission
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws PermissionExist
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
        //check to see if permission name is already exists in db
        $check = DB::table('permissions')->where('name','=',$permissionData['name'])->first();
        if($check !=null) {
            throw new PermissionExist();
        }
        $permission = Permission::create($permissionData);
        return response(['permissions' => new PermissionResource($permission), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * show a single permission
     * VueJs shows this single permission
     * @param Permission $permission
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return response(['permissions' => new PermissionResource($permission), 'message' => trans('translate.retrieved')], 200);

    }

    /**
     * update permissions
     * @param Permission $permission
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws PermissionExist
     */
    public function update(Permission $permission)
    {
        $this->authorize('update', $permission);
        $data = request()->validate([
            'name' => 'required',
            'label' => 'required'
        ]);
        //check to see if permission name is already exists in db
        $check = DB::table('permissions')->where('name','=',$data['name'])->first();
        if($check !=null) {
            throw new PermissionExist();
        }
        $permission->update($data);
        return response(['permissions' => new PermissionResource($permission), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * delete permissions
     * @param Permission $permission
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        $permission->delete();
        return response(['message' => trans('translate.deleted'), 200]);
    }
}
