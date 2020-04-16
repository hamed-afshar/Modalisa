<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'roles_id', 'email', 'password', 'confirmed', 'access_level', 'last_login', 'lock', 'last_ip', 'language',
        'tel', 'country', 'communication_media'
    ];

    protected $dates = ['last_login'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //return a path
    public function path()
    {
        return "/users/{$this->id}";
    }

    //user might belongs to many roles
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_roles')->withTimestamps();
    }

    //assign a role to user
    public function assignRole($role)
    {
        $this->roles()->save($role);
    }

    //user belongs to one subscription
    public function subscription() {
        return $this->belongsTo('App\Subscription');
    }

    // each user has many order relation
    public function orders() {
        return $this->hasMany('App\Order','user_id');
    }


}
