<?php

namespace Database\Seeders;

use App\Exceptions\ChangeHistoryNotAllowed;
use App\Order;
use App\Product;
use App\Status;
use App\Traits\HistoryTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    use HistoryTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws ChangeHistoryNotAllowed
     */
    public function run()
    {
        // create three orders, two belongs to user id of 3 and two belongs to user id of 4
        $order1 = factory(Order::class)->create([
            'user_id' => 3,
            'customer_id' => 2
        ]);

        $order2 = factory(Order::class)->create([
            'user_id' => 3,
            'customer_id' => 3

        ]);

        $order3 = factory(Order::class)->create([
            'user_id' => 4,
            'customer_id' => 25
        ]);

        $order4 = factory(Order::class)->create([
            'user_id' => 4,
            'customer_id' => 26
        ]);

        $order5 = factory(Order::class)->create([
           'user_id' => 3,
           'customer_id' => 2
        ]);

        //create product for each order
        factory(Product::class, 50)->create([
            'order_id' => $order1
        ]);

        factory(Product::class, 30)->create([
            'order_id' => $order2
        ]);

        //all products for this order should get in-office status
        $boughtStatus = Status::find(3);
        $inOfficeStatus = Status::find(4);
        factory(Product::class, 20)->create([
            'order_id' => $order3
        ]);
        $records1 = DB::table('products')->where('order_id', '=', 3)->get();
        foreach ($records1 as $item) {
            $product = Product::find($item->id);
            $this->storeHistory($product, $boughtStatus);
            $this->storeHistory($product, $inOfficeStatus);
        }

        factory(Product::class, 40)->create([
            'order_id' => $order4
        ]);

        factory(Product::class, 1)->create([
            'order_id' => $order5
        ]);
    }
}
