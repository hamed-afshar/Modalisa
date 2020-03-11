<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model

{
    protected $guarded = [];

    //return permissions path
    public function path()
    {
        return "/subscriptions/{$this->id}";
    }

}
