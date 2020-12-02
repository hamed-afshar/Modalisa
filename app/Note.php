<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * return path
     */
    public function path()
    {
        return "/notes/{$this->id}";
    }

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
