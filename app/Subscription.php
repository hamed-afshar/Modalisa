<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model

{
    protected $guarded = [];

    // subscription has one user
    public function user()
    {
        return $this->hasone('App\User');
    }

    //return permissions path
    public function path()
    {
        return "/subscriptions/{$this->id}";
    }

}
