<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Customer extends Model
{
    protected $guarded = [];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

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
