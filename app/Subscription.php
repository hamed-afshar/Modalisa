<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model

{
    protected $guarded = [];

    // subscription may belongs to many users
    public function users()
    {
        return $this->hasMany('App\User');
    }

    //assing user to subscription
    public function assignUser($user)
    {
        return $this->users()->save($user);
    }

    //return permissions path
    public function path()
    {
        return "/subscriptions/{$this->id}";
    }

}
