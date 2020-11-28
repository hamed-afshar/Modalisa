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

}
