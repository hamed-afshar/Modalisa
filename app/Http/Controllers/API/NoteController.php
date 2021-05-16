<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Note;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{

    /**
     * index all notes for the given model
     * users should have see-notes permission to be allowed
     * @param $id
     * @param $model
     * @return mixed
     * @throws AuthorizationException
     */
    public function index($id, $model)
    {
        $this->authorize('viewAny', Note::class);
        //return all notes related to the model for the given id
        $notes =  Auth::user()->notes()->where(['notable_type' =>  $model, 'notable_id' => $id])->get();
        return response(['notes' => NoteResource::collection($notes), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * form to create a note
     * VueJs modal generates this form
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Note::class);
    }

    /**
     * store notes
     * users should have create-notes permission to be allowed
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Note::class);
        $user = Auth::user();
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'notable_type' => 'required',
            'notable_id' => 'required'
        ]);
        $noteData = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'notable_type' => $request->input('notable_type'),
            'notable_id' => $request->input('notable_id')
        ];
        $user->notes()->create($noteData);
        return response(['message' => trans('translate.note_created')]);
    }

    /**
     * users with see-notes permission are allowed
     * users can only see their own notes
     * @param Note $note
     * @return mixed
     * @throws AuthorizationException
     */
    public function show(Note $note)
    {
        $this->authorize('view', $note);
        $note =  DB::table('notes')->where('id', '=', $note->id)->get();
        return response(['note' => new NoteResource($note), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * edit form
     * VueJs generates this form
     */
    public function edit()
    {

    }

    /**
     * update a note
     * users are not allowed to update notes
     * @param Note $note
     * @throws AuthorizationException
     */
    public function update(Note $note)
    {
        $this->authorize('update', $note);
    }

    /**
     * delete a note
     * @param Note $note
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Note $note)
    {
        dd('here');
        $this->authorize('delete', $note);
        $note->delete();
        return response(['message' => trans('translate.deleted')], 200);
    }
}
