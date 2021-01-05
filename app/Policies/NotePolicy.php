<?php

namespace App\Policies;

use App\Note;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether user is locked or not confirmed first
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if($user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }

    /**
     * Determine whether the user can view any notes.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user) {
        if($user->checkPermission('see-notes')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the note.
     * Use can only see its own notes
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function view(User $user, Note $note)
    {
        if($user->checkPermission('see-notes') && $user->id == $note->user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can create notes.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
       if($user->checkPermission('create-notes')) {
           return true;
       }
    }

    /**
     * Determine whether the user can update the note.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function update(User $user, Note $note)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the note.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function delete(User $user, Note $note)
    {
        if($user->checkPermission('delete-notes') && $note->notable->user_id == $user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the note.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function restore(User $user, Note $note)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the note.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function forceDelete(User $user, Note $note)
    {
        //
    }
}
