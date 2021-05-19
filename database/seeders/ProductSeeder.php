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

        factory(Product::class, 20)->create([
            'order_id' => $order3
        ]);

        factory(Product::class, 40)->create([
            'order_id' => $order4
        ]);

        factory(Product::class, 1)->create([
            'order_id' => $order5
        ]);
    }
}
