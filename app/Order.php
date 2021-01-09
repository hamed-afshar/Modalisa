<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * each order belongs to a user
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    /**
     * each order may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each order may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imagable');
    }

    /**
     * each order may have many costs
     */
    public function costs()
    {
        return $this->morphMany('App\Cost', 'costable');
    }

    /**
     * each order may have many products
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * each order belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
