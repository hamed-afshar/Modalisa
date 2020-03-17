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

    //each role belongs to one user
    public function user()
    {
        return $this->hasOne('App\user');
    }

}
