<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    protected $guarded = [];

    /*
     * return transaction path
     */
    public function path()
    {
        return "/transactions/{$this->id}";
    }

    /*
     * each transaction belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}