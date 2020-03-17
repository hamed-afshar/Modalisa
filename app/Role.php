<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    //each role assigned to one user
    public function userRole()
    {
        return $this->hasOne('App\UserRole');
    }

    //each role has many assigned permissions
    public function assignedPermissions()
    {
        return $this->hasMany('App\RolePermission');
    }

    //return role path
    public function path()
    {
        return "/roles/{$this->id}";

    }

}
