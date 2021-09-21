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
        $kargotList1 = array();
        $kargotList2 = array();
        $kargoList3 = array();
        //create three kargos, two for user id of 3 and one for user id of 4
        $kargo1 = factory(Kargo::class)->create([
            'user_id' => 3
        ]);

        // get the list of products for order with id of 1
        // first change the status to bought then change it to in-office
        // finally create kargo from this list
        $records1 = DB::table('products')->where('order_id', '=', 1)->get();
        $boughtStatus = Status::find(3);
        $inOfficestatus = Status::find(4);
        foreach ($records1 as $item) {
            $product = Product::find($item->id);
            $this->storeHistory($product, $boughtStatus);
            $this->storeHistory($product, $inOfficestatus);
            $kargoList1[] = $product;
        }

        //set kargo for these lists
        $kargo1->setKargo($kargoList1);
    }
}
