<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $incrementing = false;
   

    public function path()
    {
        return "/orders/{$this->id}";
        
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}