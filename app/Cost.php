<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    /**
     * each cost may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }
}
