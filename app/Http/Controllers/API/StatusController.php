<?php

namespace App\Http\Controllers\API;

use App\Exceptions\StatusExist;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatusResource;
use App\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    /**
     * only SystemAdmin can index statuses
     * index all statuses
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Status::class);
        $statuses =  Status::all();
        return response(['statuses'=>StatusResource::collection($statuses), 'message' => trans('translate.retrieved')],200);
    }

    /**
     * only SystemAdmin can create statuses
     * store status
     * @param Request $request
     * @throws AuthorizationException
     * @throws StatusExist
     */
    public function store(Request $request)
    {
        $this->authorize('create', Status::class);
        $request->validate([
            'priority' =>'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        $statusData = [
            'priority' =>$request->input('priority'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];
        $check = DB::table('statuses')->where('name','=',$statusData['name'])->first();
        if($check != null) {
            throw new StatusExist();
        }
        $status = Status::create($statusData);
        return response(['statuses' => new StatusResource($status), 'message' => trans('translate.retrieved')],200);
    }

    /**
     * show a single status
     * VueJs show this single status
     * @param Status $status
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function show(Status $status)
    {
        $this->authorize('view', $status);
        return response(['statuses' => new StatusResource($status), 'message' => trans('translate.retrieved')],200);
    }

    /**
     * only SystemAdmin can update statuses
     * update status
     * @param Status $status
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws StatusExist
     */
    public function update(Status $status)
    {
        $this->authorize('update', $status);
        $data = request()->validate([
            'priority' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        $check = DB::table('statuses')->where('name','=',$data['name'])->first();
        if($check != null) {
            throw new StatusExist();
        }
        $status->update($data);
        return response(['statuses' => new StatusResource($status), 'message' => trans('translate.retrieved')],200);

    }

    /**
     * only SystemAdmin can delete statuses
     * delete status
     * @param Status $status
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function destroy(Status $status)
    {
        $this->authorize('delete', $status);
        $status->delete();
        return response(['message' => trans('translate.deleted'), 200]);
    }
}
