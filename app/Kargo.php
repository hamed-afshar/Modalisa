<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
        return "/kargos/{$this->id}";
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
     */
    public function checkLimit(User $user, $productList)
    {
        $kargoLimit = $user->subscription->kargo_limit;
        if (count($productList) < $kargoLimit) {
            return false;
        } else {
            return true;
        }
    }

    public function createKargo(User $user, $kargoData, $kargoList)
    {
        DB::beginTransaction();
        $kargo = $user->kargos()->create($kargoData);
        $productList = array();
        foreach ($kargoList as $item) {
            $product = Product::find($item);
            $productList[] = $product;
        }
        // function to check number of items in product list is equal to the kargo_limit value defined in subscription table
        // if checkLimit function fails, then all changes will rollback and kargo wont be created
        if ($this->checkLimit($user, $productList)) {
            dump('checklimit-true');
            $this->setKargo($productList);
            $this->refresh();
            DB::commit();
        } else {
            dump('checklimit-false');
            DB::rollBack();
            return Redirect::back()->withErrors(['msg', trans('translate.kargo_limit')]);
        }
    }


}
