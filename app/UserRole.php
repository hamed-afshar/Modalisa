<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $guarded = [];

    //each role belongs to one user
    public function user()
    {
        return $this->hasOne('App\user');
    }

}
