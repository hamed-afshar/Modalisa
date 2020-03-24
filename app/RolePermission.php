<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [];

    public function roleOwner()
    {
        return $this->belongsTo('App\Role')->withDefault();
    }

    public function permissionOwner()
    {
        return $this->belongsTo('App\Permission')->withDefault();
    }

    public function path()
    {
        return "/role-permissions/{$this->id}";
    }
}
