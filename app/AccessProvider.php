<?php


namespace App;

use App\Permission;
use App\UserRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

class AccessProvider
// This class provides access permission for users
{
    private $role;
    private $userID;
    private $request;
    private $requestID;

    /**
     * AccessProvider constructor.
     * @param $userID
     * @param $request
     */

    public function __construct($userID, $request)
    {
        $this->userID = $userID;
        $this->request = $request;
        $this->role = UserRole::find($userID)->role_id;
        if (!Permission::where('name', $this->request)->first()) {
            $this->requestID = null;
        } else {
            $this->requestID = Permission::where('name', $this->request)->first()->id;
        }
    }


    /**
     * return permission
     */
    public function getPermission()
    {
        if (User::find($this->userID)->first()->confirmed = 0) {
            return 'not-confirmed';
        }

        if (User::find($this->userID)->first->locked == 1) {
            return 'locked';
        }

        if ($this->requestID == null) {
            return false;
        } elseif (Role::find($this->role)->assignedPermissions->where('permission_id', $this->requestID)->first() != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * return access denied
     */
    public function accessDenied()
    {
        return redirect('access-denied');
    }

    /**
     *return pending for confirmation
     */
    public function pendingForConfirmation()
    {
        return redirect('pending-for-confirmation');
    }

    /**
     * return locked
     */
    public function userLocked()
    {
        return redirect('locked');
    }


}
