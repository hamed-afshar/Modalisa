<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    //each role has many assigned permissions
    public function assignedPermissions()
    {
        return $this->hasMany('App\RolePermission');
    }

}
