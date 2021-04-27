<?php

namespace App;

use App\Exceptions\SubscriptionExist;
use App\Exceptions\WithoutSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class Kargo extends Model
{
    protected $guarded = [];

    /**
     * return path
     */
    public function path()
    {
        return "api/kargos/{$this->id}";
    }

    /**
     * each kargo may have many products
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * each kargo may have many notes
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }

    /**
     * each kargo may have many images
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imagable');
    }

    /**
     * each kargo may have many costs
     */
    public function costs()
    {
        return $this->morphMany('App\Cost', 'costable');
    }

    /** each kargo belongs to a user */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /** set kargo number for product's list
     * @param $productList
     */
    public function setKargo($productList)
    {
        foreach ($productList as $product) {
            $this->products()->save($product);
        }
    }

    /** check kargo limit based on user's subscription
     * @param User $user
     * @param $productList
     * @return bool
     * @throws WithoutSubscription
     */
    public function checkLimit(User $user, $productList)
    {
        //show error if user do not have any subscription
        if($user->subscription == null) {
            throw new WithoutSubscription();
        }
        $kargoLimit = $user->subscription->kargo_limit;
        if (count($productList) < $kargoLimit) {
            return false;
        } else {
            return true;
        }
    }


}
