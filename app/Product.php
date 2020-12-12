<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded =[];

    /**
     * return product path
     */

    public function path()
    {
        return "/products/{$this->path()}";
    }


    /**
     * each product has many histories
     */
    public function histories()
    {
        return $this->hasMany('App\History');
    }

    /**
     * change history for product
     */
    public function changeHistory($status)
    {
        $data = [
            'product_id' => $this->id,
            'status_id' => $status->id
        ];
        $this->histories()->create($data);
    }

    /**
     * each product may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each product belongs to a kargo
     */
    public function kargo()
    {
        return $this->belongsTo('App\Kargo');
    }

    /**
     * each product may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imagable');
    }
}
