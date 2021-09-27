<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
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
     * return status path
     */
    public function path()
    {
        return "api/statuses/{$this->id}";
    }


    /**
     * each status has many histories
     */
    public function histories()
    {
        return $this->hasMany('App\History');
    }

}
