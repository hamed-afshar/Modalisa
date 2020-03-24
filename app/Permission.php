<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    //return a path
    public function path()
    {
        return "/permissions/{$this->id}";
    }

    public function rolePermissions()
    {
        return $this->hasMany('App\RolePermission');
    }
}
