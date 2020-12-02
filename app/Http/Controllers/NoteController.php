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
        $this->authorize('viewAny', $note);
        return Auth::user()->notes;
    }

    /**
     * form to create a note
     * VueJs modal geneates this form
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

    /**
     * edit form
     * VueJs generates this form
     */
    public function edit()
    {

    }

    /**
     * update a note
     */
    public function update($note)
    {
        $this->authorize('update', $note);
    }
}
