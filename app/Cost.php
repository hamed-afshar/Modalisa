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

    /**
     * each cost may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image' , 'imagable');
    }
}
