<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded=[];

    /**
     * each image belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * each image belongs to a model
     */
    public function imagable()
    {
        return $this->morphTo();
    }
}
