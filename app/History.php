<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class History extends Model
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
     *  return path
     */
    public function path()
    {
        return "api/histories/{$this->id}";
    }

    /**
     * history belongs to status
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * history belongs to product
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}
