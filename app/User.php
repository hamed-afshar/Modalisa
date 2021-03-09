<?php

namespace App;

use App\Events\UserRegisteredEvent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

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
        // if user has the given permission then true flag will return, otherwise false will be the return value
        $permissions = $this->permissions();
        $flag = false;
        foreach ($permissions as $per) {
            if ($per->name === $permission) {
                $flag = true;
            }
        }
        return $flag;
    }

    /**
     * check to see if user is a SystemAdmin
     */
    public function isAdmin()
    {
        if ($this->role()->pluck('name')->contains('SystemAdmin')) {
            return true;
        }
    }

    /**
     * check to see if user has supper privilege rolls
     * @return bool
     */
    public function checkPrivilegeRole()
    {
        $privilegeRoleArray=['BuyerAdmin', 'SystemAdmin'];
        $userRoles = $this->role()->pluck('name');
        foreach ($privilegeRoleArray as $privilegeRole)
        {
            foreach ($userRoles as $role)
            {
                if($privilegeRole == $role)
                {
                    return true;
                }
            }
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
     * user belongs to one role
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
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

    /**
     * each user has many products
     */
    public function products()
    {
        return $this->hasManyThrough('App\Product', 'App\Order');
    }

    /**
     * each user has many orders
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    /**
     * each user has many customers
     */
    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    /**
     * each user has many notes
     */
    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    /**
     * each user has many images
     */
    public function images()
    {
        return $this->hasMany('App\Image');
    }

    /**
     * each user has many costs
     */
    public function costs()
    {
        return $this->hasMany('App\Cost');
    }

    /**
     * each user has many kargos
     */
    public function kargos()
    {
        return $this->hasMany('App\Kargo');
    }
}
