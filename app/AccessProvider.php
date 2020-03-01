<?php


namespace App;
use App\UserRole;
use Illuminate\Database\Eloquent\Collection;

class AccessProvider
// This class provides access permission for users
{
    public $role;
    public $userID;
    public $request;

    /**
     * AccessProvider constructor.
     * @param $userID
     * @param $request
     */

    public function __construct($userID, $request)
    {
        $this->userID = $userID;
        $this->request = $request;
        $this->role = UserRole::find(1);
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {

        return $this->request;
    }




}
