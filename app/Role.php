<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    /**
     * return role path
     */
    public function path()
    {
        return "api/roles/{$this->id}";
    }

    /**
     * each role has many users
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * each role might belongs to many permissions
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permissions');
    }

    /**
     * allow permission for roles
     */
    public function allowTo($permission)
    {
        $this->permissions()->attach($permission);
    }

    /**
     * disallow permissions for roles
     */
    public function  disAllowTo($permission)
    {
        $this->permissions()->detach($permission);
    }

    /**
     * change user's role
    */
    public function changeRole($user)
    {
        $this->users()->save($user);
    }



}
