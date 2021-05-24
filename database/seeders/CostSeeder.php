<?php

namespace Database\Seeders;

use App\Cost;
use Illuminate\Database\Seeder;

class CostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * create cost for retailer with id of 3
         * all costs are related to order number 1
         */
        factory(Cost::class, 10)->create([
            'user_id' => 3,
            'amount' => 100,
            'description' => 'cost for order number 1',
            'costable_type' => 'App\Product',
            'costable_id' => 1
        ]);

        /**
         * create cost for retailer with id of 5
         * all costs are related to order number 100
         */
        factory(Cost::class, 20)->create([
            'user_id' => 4,
            'amount' => 200,
            'description' => 'cost for order number 1',
            'costable_type' => 'App\Product',
            'costable_id' => 100
        ]);
    }
}
