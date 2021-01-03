<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model

{
    protected $guarded = [];
    /**
     * return path
     */
    public function path()
    {
        return "/subscriptions/{$this->id}";
    }

    /**
     * each subscription may have many users
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * change user's subscription
     * @param $user
     * @return false|Model
     */
    public function changeSubscription($user)
    {
        return $this->users()->save($user);
    }

}
