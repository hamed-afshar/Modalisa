<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    //Role might belongs to many users
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_roles')->withTimestamps();
    }

    //Role might belongs to many permissions
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permissions')->withTimestamps();
    }

    //assign permission to role
    public function allowTo($permission)
    {
        $this->permissions()->sync($permission, false);
    }

    //return role path
    public function path()
    {
        return "/roles/{$this->id}";
    }

}
