<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Note extends Model
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
     * return path
     */
    public function path()
    {
        return "api/notes/{$this->id}";
    }

    /**
     * each note belongs to a model
     */
    public function notable()
    {
        return $this->morphTo();
    }

    /**
     * each note belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
