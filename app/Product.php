<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Product extends Model
{
    protected $guarded =[];

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
     * return product path
     */
    public function path()
    {
        return "/products/{$this->path()}";
    }


    /**
     * each product has many histories
     */
    public function histories()
    {
        return $this->hasMany('App\History');
    }

    /**
     * each product may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each product belongs to a kargo
     */
    public function kargo()
    {
        return $this->belongsTo('App\Kargo');
    }

    /**
     * each product may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imagable');
    }

    /**
     * each product may have many costs
     */
    public function costs()
    {
        return $this->morphMany('App\Cost', 'costable');
    }

    /**
     * each product belongs to an order
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    /** each product belongs to a user */
    public function user()
    {
        return $this->order->user;
    }

}
