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
}
