<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * each order belongs to a user
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
