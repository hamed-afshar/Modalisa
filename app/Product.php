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
}
