<?php

/**
 * trait to handle kargo functions
 */

namespace App\Traits;

use App\Product;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

trait KargoTrait
{
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
        if ($kargo->checkLimit($user, $productList)) {
            $kargo->setKargo($productList);
            $kargo->refresh();
            DB::commit();
        } else {
            DB::rollBack();
            return Redirect::back()->withErrors(['msg', trans('translate.kargo_limit')]);
        }
    }
}
