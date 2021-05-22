<?php

/**
 * trait to handle history functions
 */

namespace App\Traits;

use App\Product;
use Illuminate\Support\Facades\DB;

trait HistoryTrait
{
    /**
     * function to get the latest status of the given product
     * @param Product $product
     * @return int
     */
    public function getStatus(Product $product): int
    {
        $latestHistory = DB::table('histories')
            ->where('product_id', '=', $product->id)
            ->orderBy('id', 'desc')->first();
        return $latestHistory->status_id;
    }
}
