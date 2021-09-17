<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Cost extends Model
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

    /** return path */
    public function path()
    {
        return "api/costs/{$this->id}";
    }

    /**
     * each cost belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    /**
     * each cost may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each cost may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image' , 'imagable');
    }

    /**
     * each cost belongs to a model
     */
    public function costable()
    {
        return $this->morphTo();
    }
}
