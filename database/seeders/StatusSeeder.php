<?php

namespace Database\Seeders;

use App\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Status::class)->create([
            'priority' => '0',
            'name' => 'Order Deleted',
            'description' => 'Order is deleted from the system'
        ]);

        factory(Status::class)->create([
            'priority' => '1',
            'name' => 'Order Created',
            'description' => 'Order is created by a retailer'
        ]);

        factory(Status::class)->create([
            'priority' => '2',
            'name' => 'Order Bought',
            'description' => 'Order is bought by BuyerAdmin'
        ]);

        factory(Status::class)->create([
            'priority' => '3',
            'name' => 'Order in office',
            'description' => 'Order has arrived to the office'
        ]);

        factory(Status::class)->create([
            'priority' => '4',
            'name' => 'Order in kargo to Iran',
            'description' => 'Order is on the way to Iran'
        ]);

        factory(Status::class)->create([
            'priority' => '5',
            'name' => 'Order in Iran',
            'description' => 'Order has arrived to Iran'
        ]);

        factory(Status::class)->create([
            'priority' => '6',
            'name' => 'Order in kargo from Iran',
            'description' => 'Order is on the way to Turkey'
        ]);

        factory(Status::class)->create([
            'priority' => '7',
            'name' => 'Order returned',
            'description' => 'Order is returned to the site'
        ]);

        factory(Status::class)->create([
            'priority' => '8',
            'name' => 'Order refunded',
            'description' => 'money for the order has refunded'
        ]);

        factory(Status::class)->create([
            'priority' => '9',
            'name' => 'Order is edited',
            'description' => 'Order details is edited'
        ]);



    }
}
