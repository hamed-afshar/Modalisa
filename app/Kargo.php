<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kargo extends Model
{
    /**
     * each kargo have many products
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
}
