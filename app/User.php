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
        'name', 'email', 'password', 'confirmed', 'access_level', 'last_login', 'lock', 'last_ip', 'language',
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
    
    // each user had many order relation
    public function orders() 
    {
        return $this->hasMany(Order::class, 'users_id');
    }
   
    //function to return a path to any user
    public function path() 
    {
        return '/user/' . $this->id;
    }
    
    
}
