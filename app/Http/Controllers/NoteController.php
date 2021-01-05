<?php

namespace App\Http\Controllers;

use App\Note;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * index all notes for the given object
     * users should have see-notes permission to be allowed
     * @throws AuthorizationException
     */
    public function index($id, $model)
    {
        dd('here');
        $this->authorize('viewAny', Note::class);
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
     * @param Request $request
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
        return Auth::user()->notes->find($note);
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
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
    }
}
