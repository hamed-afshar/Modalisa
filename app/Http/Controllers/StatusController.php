<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

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
        return Status::all();
    }

    /**
     * form to create status
     * VueJs Modal
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Status::class);
    }

    /**
     * only SystemAdmin can create statuses
     * store status
     * @param Request $request
     * @throws AuthorizationException
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
      Status::create($statusData);
    }

    /**
     * show a single status
     * VueJs show this single status
     * @param Status $status
     * @return Status
     * @throws AuthorizationException
     */
    public function show(Status $status)
    {

        $this->authorize('view', $status);
        return $status;
    }

    /**
     * Form to update status
     * VueJs Modal
     * @param Status $status
     * @throws AuthorizationException
     */
    public function edit(Status $status)
    {
        $this->authorize('update', $status);
    }

    /**
     * only SystemAdmin can update statuses
     * update status
     * @param Status $status
     * @throws AuthorizationException
     */
    public function update(Status $status)
    {
        $this->authorize('update', $status);
        $data = request()->validate([
            'priority' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        $status->update($data);
    }

    /**
     * only SystemAdmin can delete statuses
     * delete status
     * @param Status $status
     * @throws AuthorizationException
     */
    public function destroy(Status $status)
    {
        $this->authorize('delete', $status);
        $status->delete();
    }
}
