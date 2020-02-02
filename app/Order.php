<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'orderID';


    public function path()
    {
        return "/orders/{$this->orderID}";
        
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}