<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $guarded = [];

    /**
     * return the path
     * @return string
     */
    public function path()
    {
        return "api/permissions/{$this->id}";
    }

    /**
     * each permission may have many roles
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_permissions')->withTimestamps();
    }
}
