<?php

namespace Database\Seeders;

use App\Order;
use App\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create three orders belongs to user id 3 which is a retailer
        $order1 = factory(Order::class)->create([
            'user_id' => 3
        ]);

        $order2 = factory(Order::class)->create([
            'user_id' => 3
        ]);

        $order3 = factory(Order::class)->create([
            'user_id' => 3
        ]);

        //create product for each order
        factory(Product::class, 50)->create([
            'order_id' => $order1
        ]);

        factory(Product::class, 30)->create([
            'order_id' => $order2
        ]);

        factory(Product::class, 20)->create([
            'order_id' => $order3
        ]);

    }
}
