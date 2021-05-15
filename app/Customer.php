<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    /**
     * return path
     */
    public function path()
    {
        return "api/customers/{$this->id}";
    }

    /**
     * each customer has many orders
     */

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    /**
     * each customer belongs to a user
     */
    public function user()
    {
       return $this->belongsTo('App\User');

    }

    /**
     * each customer may have notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }
}
