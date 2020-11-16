<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    /*
     * index all statuses
     */
    public function index()
    {
        $this->authorize('viewAny', Status::class);
        return Status::all();
    }

    /*
     * status create form
     * VueJs Modal
     */
    public function create()
    {
        $this->authorize('create', Status::class);
    }

    /*
     * store status
     */
    public function store()
    {
        $this->authorize('create', Status::class);
        Status::create(request()->validate([
            'priority' =>'required',
            'name' => 'required',
            'description' => 'required'
        ]));
    }

    /*
     * show a single status
     * VueJs show this single status
     */
    public function show(Status $status)
    {
        $this->authorize('vies', $status);
        return $status;
    }

    /*
     * Status update form
     * VueJs Modal
     */
    public function edit(Status $status)
    {
        $this->authorize('update', $status);
    }

    /*
     * update status
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

    /*
     * delete status
     */
    public function delete(Status $status)
    {
        $this->authorize('delete', $status);
        $status->delete();
    }



}
