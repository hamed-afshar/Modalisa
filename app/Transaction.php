<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;

class Transaction extends Model
{
    protected $guarded = [];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * return transaction path
     */
    public function path()
    {
        return "api/transactions/{$this->id}";
    }

    /**
     * each transaction belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * each transaction may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each transaction may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imagable');
    }

    /**
     * each transaction may have many costs
     */
    public function costs()
    {
        return $this->morphMany('App\Cost', 'costable');
    }
}
