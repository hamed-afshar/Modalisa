<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $guarded = [];

    //each role assigned to one user
    public function roleOwner()
    {
        return $this->belongsTo('App\Role')->withDefault();
    }

    //each user has one role
    public function userOwner()
    {
        return $this->belongsTo('App\User')->withDefault();
    }

    //return a path
    public function path()
    {
        return '/user-roles/' . $this->id;
    }
}
