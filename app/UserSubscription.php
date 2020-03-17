<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $guarded = [];

    //belongs to subscription
    public function owner()
    {
       return $this->belongsTo('App\subscription')->withDefault();
    }

    //return a path to a subscription
    public function path()
    {
        return "/user-subscriptions/{$this->id}";
    }

}
