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
}
