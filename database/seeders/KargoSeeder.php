<?php

namespace Database\Seeders;

use App\Kargo;
use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KargoSeeder extends Seeder
{
    /**
     * create a kargo seeder for user with id of 3 as a retailer
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
        $kargo2 = factory(Kargo::class)->create([
        'user_id' => 3
        ]);

        $kargo3 = factory(Kargo::class)->create([
            'user_id' => 4
        ]);

        //retrieved list of products for order with id of 1
        $records1 = DB::table('products')->where('order_id', '=', 1)->get();
        foreach ($records1 as $item) {
            $product = Product::find($item->id);
            $kargoList1[] = $product;
        }
        //retrieved list of products for order with id of 2
        $records2 = DB::table('products')->where('order_id', '=', 2)->get();
        foreach ($records2 as $item) {
            $product = Product::find($item->id);
            $kargoList2[] = $product;
        }

        //retrieved list of products for order with id of 3
        $records3 = DB::table('products')->where('order_id', '=', 3)->get();
        foreach ($records3 as $item) {
            $product = Product::find($item->id);
            $kargoList3[] = $product;
        }

        //set kargo for these lists
        $kargo1->setKargo($kargoList1);
        $kargo2->setKargo($kargoList2);
        $kargo2->setKargo($kargoList3);
    }
}
