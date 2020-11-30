<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * each note belongs to a model
     */
    public function notable()
    {
        return $this->morphTo();
    }

    /**
     * each note belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
