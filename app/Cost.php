<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $guarded = [];

    /** return path */
    public function path()
    {
        return "/costs/{$this->id}";
    }

    /**
     * each cost belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('APP\User');
    }
    /**
     * each cost may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each cost may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image' , 'imagable');
    }

    /**
     * each cost belongs to a model
     */
    public function costable()
    {
        return $this->morphTo();
    }
}
