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
        $this->requestID = Permission::where('name', $this->request)->first()->id;
    }

    /**
     * @return mixed
     */
    public function getRequestID()
    {
        return $this->requestID;
    }


    /**
     * return permission
     */
    public function getPermission()
    {
        $permission = Role::find($this->role)->rolepermissions;
//        return $permission->search($this->requestID);
        return $permission;

    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }


}
