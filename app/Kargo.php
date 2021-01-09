<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kargo extends Model
{
    protected $guarded = [];

    /**
     * return path
     */
    public function path()
    {
        return "/kargos/{$this->id}";
    }

    /**
     * each kargo may have many products
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * each kargo may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each kargo may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imagable');
    }

    /**
     * each kargo may have many costs
     */
    public function costs()
    {
        return $this->morphMany('App\Cost', 'costable');
    }

    /** each kargo belongs to a user */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


}
