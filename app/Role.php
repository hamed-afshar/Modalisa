<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    /*
     * Role has many users
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /*
     * Role might belongs to many permissions
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permissions');
    }

    /*
     * allow permission for roles
     */
    public function allowTo($permission)
    {
        $this->permissions()->attach($permission);
    }

    /*
     * disallow permissions for roles
     */
    public function  disAllowTo($permission)
    {
        $this->permissions()->detach($permission);
    }

    /*
   * assign a user to role
   */
    public function assignUser($user)
    {
        $this->users()->save($user);
    }

    /*
     * return role path
     */
    public function path()
    {
        return "/roles/{$this->id}";
    }

}
