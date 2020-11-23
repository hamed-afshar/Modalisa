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
            'status_id' => $status->id
        ];
        $this->histories()->update($data);
    }
}
