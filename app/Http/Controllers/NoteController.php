<?php

namespace App\Http\Controllers;

use App\Note;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * index notes
     */
    public function index(Note $note)
    {
        $this->authorize('viewAny', Note::class);
        return Auth::user()->notes;
    }

    /**
     * form to create a note
     * VueJs modal generates this form
     */
    public function create()
    {
        $this->authorize('create', Note::class);
    }

    /**
     * store notes
     */
    public function store()
    {
        $this->authorize('create', Note::class);
        $user = Auth::user();
        $data = request()->validate([
            'title' => 'required',
            'body' => 'required',
            'notable_type' => 'required',
            'notable_id' => 'required'
        ]);
        $user->notes()->create($data);
    }

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
     */
    public function update(Note $note)
    {
        $this->authorize('update', $note);
    }

    /**
     * delete a note
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
    }
}
