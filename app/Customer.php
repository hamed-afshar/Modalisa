<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * each customer has many orders
     */

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
