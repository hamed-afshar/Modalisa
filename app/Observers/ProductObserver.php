<?php

namespace App\Observers;

use App\History;
use App\Product;

class ProductObserver
{
    /**
     * Handle the product "created" event.
     * Created Orders status should set to 2
     * @param Product $product
     * @return void
     */
    public function created(Product $product)
    {

        History::create([
            'product_id'=> $product->id,
            'status_id' => 2
        ]);
    }

    /**
     * Handle the product "updated" event.
     *
     * @param Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the product "restored" event.
     *
     * @param Product $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param Product $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
