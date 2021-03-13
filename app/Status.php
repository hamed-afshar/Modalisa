<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $guarded = [];

    /**
     * return status path
     */
    public function path()
    {
        return "api/statuses/{$this->id}";
    }


    /**
     * each status has many histories
     */
    public function histories()
    {
        return $this->hasMany('App\History');
    }

}
