<?php

namespace App;

use App\Events\UserRegisteredEvent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\True_;

class User extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => UserRegisteredEvent::class,
    ];

    /**
     * return path
     */
    public function path()
    {
        return "/users/{$this->id}";
    }

    /**
     * user belongs to one role
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * get user's permissions
     */
    public function permissions()
    {
        $role = auth()->user()->role;
        return $role->permissions;
    }

    /**
     * check user's permissions for a specific action
     * @param $permission
     * @return void
     */
    public function checkPermission($permission): bool
    {
        $permissions = $this->permissions();
        foreach ($permissions as $per) {
            if ($per->name === $permission) {
                return true;
            }
            return false;
        }
    }

    /**
     * check to see if user is a systemadmin
     */
    public function isAdmin()
    {
        if ($this->role()->pluck('name')->contains('SystemAdmin')) {
            return true;
        }
    }

    /**
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

    /**
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

    /**
     * user belongs to one subscription
     */
    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }


    /**
     * each user has many transactions
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    /** each user has many products */
    public function products()
    {
        return $this->hasManyThrough('App\Product', 'App\Order');
    }
}
