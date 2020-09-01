<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

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

    /*
     * return path
     */
    public function path()
    {
        return "/users/{$this->id}";
    }

    /*
     * user belongs to one role
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /*
     * get user's permissions
     */
    public function permissions()
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
    }

    /*
     * check to see if user is a systemadmin
     */
    public function isAdmin()
    {
        if ($this->roles()->pluck('name')->contains('SystemAdmin')) {
            return true;
        }
    }

    /*
     * check to see if user is locked
     */
    public function isLocked()
    {
        if ($this->locked == 1) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * check to see if user is confirmed
     */
    public function isConfirmed()
    {
        if ($this->confirmed == 1) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * user belongs to one subscription
     */
    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }

    /*
     * each user has many order relation
     */
    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id');
    }


}
