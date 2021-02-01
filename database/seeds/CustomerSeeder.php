<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * to create a default customer for retailers who do not want to add customers to their orders
     * default customer_id will be 1
     * @return void
     */
    public function run()
    {
        factory(App\Customer::class)->create([
            'name' => 'My Self',
            'tel' => '11223344556',
            'communication_media' => 'WhatsApp',
            'communication_id' => 'My Self'
        ]);
    }
}
