<?php

namespace Database\Seeders;

use App\Exceptions\ChangeHistoryNotAllowed;
use App\Kargo;
use App\Product;
use App\Status;
use App\Traits\HistoryTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KargoSeeder extends Seeder
{
    use HistoryTrait;

    /**
     * create a kargo seeder for user with id of 3 as a retailer
     * @throws ChangeHistoryNotAllowed
     */
    public function run()
    {
        /**
         * create one kargo for first 50 products included in order number1
         */
        $kargo1 = factory(Kargo::class)->create([
            'user_id' => 3
        ]);

        /**
         * get the list of products for order with id of 1
         * first change the status to bought then change it to in-office
         * finally create kargo from this list
         */
        //$kargotList1 = array();
        $boughtStatus = Status::find(3);
        $inOfficestatus = Status::find(4);
        $records1 = DB::table('products')->where('order_id', '=', 1)->get();
        foreach ($records1 as $item) {
            $product = Product::find($item->id);
            $this->storeHistory($product, $boughtStatus);
            $this->storeHistory($product, $inOfficestatus);
            $kargoList1[] = $product;
        }
        $kargo1->setKargo($kargoList1);

        /**
         * change last history for products in order number 2 to in office
         */
        $records2 = DB::table('products')->where('order_id', '=', 2)->get();
        foreach ($records2 as $item) {
            $product = Product::find($item->id);
            $this->storeHistory($product, $boughtStatus);
            $this->storeHistory($product, $inOfficestatus);
            $kargoList1[] = $product;
        }
    }

}
