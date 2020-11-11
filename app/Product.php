<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded =[];

    /*
     * return product path
     */

    public function path()
    {
        return "/products/{$this->path()}";
    }

    /*
     * each product belongs to a status
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
}
