<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    protected $guarded = [];

    /**
     * return transaction path
     */
    public function path()
    {
        return "/transactions/{$this->id}";
    }

    /**
     * each transaction belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * each transaction may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each transaction may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imagable');
    }

    /**
     * each transaction may have many costs
     */
    public function costs()
    {
        return $this->morphMany('App\Cost', 'costable');
    }
}
