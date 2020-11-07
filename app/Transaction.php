<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        $this->belongsTo('App\Transaction');
    }
}
