<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $guarded = [];

    /*
     * return status path
     */
    public function path()
    {
        return "/statuses/{$this->id}";
    }

    /*
     * each status has many products
     */
    public function products()
    {
        return $this->hasMany('App\Products');
    }
}
