<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Image extends Model
{
    protected $guarded=[];

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
     * return path
     */
    public function path()
    {
        return "api/images/{$this->id}";
    }

    /**
     * each image belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * each image belongs to a model
     */
    public function imagable()
    {
        return $this->morphTo();
    }
}
