<?php

namespace App\Policies;

use App\Image;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * First : Determine whether user is locked or not confirmed
     * Second: also user must belongs to retailers roles group to be allowed.
     * These two condition is enough for image uploading
     * @param User $user
     * @return false
     */

    public function before(User $user)
    {
        if ($user->isLocked() || !($user->isConfirmed())) {
            return false;
        }
    }

    /**
     * Determine whether the user can view any images.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if($user->checkPermission('see-images')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the image.
     * users can only see their own records
     * @param User $user
     * @param Image $image
     * @return mixed
     */
    public function view(User $user, Image $image)
    {
        if($user->checkPermission('see-images') && $user->id == $image->user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can create images.
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->checkPermission('create-images')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the image.
     * users can only update their own images
     * @param User $user
     * @param Image $image
     * @return mixed
     */
    public function update(User $user, Image $image)
    {
        if($user->checkPermission('create-images') && $user->id == $image->user->id) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the image.
     *
     * @param User $user
     * @param Image $image
     * @return mixed
     */
    public function delete(User $user, Image $image)
    {
        if($user->checkPermission('delete-images') && $user->id == $image->user->id ) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the image.
     *
     * @param User $user
     * @param Image $image
     * @return mixed
     */
    public function restore(User $user, Image $image)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the image.
     *
     * @param User $user
     * @param Image $image
     * @return mixed
     */
    public function forceDelete(User $user, Image $image)
    {
        //
    }
}
