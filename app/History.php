<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $guarded = [];

    /**
     *  return path
     */
    public function path()
    {
        return "api/histories/{$this->id}";
    }

    /**
     * history belongs to status
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * history belongs to product
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}
