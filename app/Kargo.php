<?php

namespace App;

use App\Exceptions\SubscriptionExist;
use App\Exceptions\WithoutSubscription;
use App\Traits\HistoryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use DateTimeInterface;

class Kargo extends Model
{
    use HistoryTrait;

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

    /**
     * set kargo number for product's list
     * process items one by one and change the history to in-kargo-to-destination(next status = 5)
     * @param $productList
     * @throws Exceptions\ChangeHistoryNotAllowed
     */
    public function setKargo($productList)
    {
        $status = Status::find(5);
        foreach ($productList as $product) {
            $this->storeHistory($product, $status);
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
