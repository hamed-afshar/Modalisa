<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model

{
    protected $guarded = [];

    // each subscription has many assigned users
    public function userSubscriptions()
    {
        return $this->hasMany('App\UserSubscription');
    }

    //return permissions path
    public function path()
    {
        return "/subscriptions/{$this->id}";
    }

}
