<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];
    public function path()
    {
        return "/permissions/{$this->id}";
    }
}
