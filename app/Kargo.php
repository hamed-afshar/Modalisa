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
}
