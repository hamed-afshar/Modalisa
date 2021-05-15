<?php
namespace Database\Seeders;
use App\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeders.
     * @return void
     */
    public function run()
    {
        /**
         * to create a default customer for retailers who do not want to add customers to their orders
         * default customer_id will be 1
         */
        factory(Customer::class)->create([
            'name' => 'My Self',
            'tel' => '11223344556',
            'communication_media' => 'WhatsApp',
            'communication_id' => 'My Self'
        ]);

        /**
         * create 20 customers for retailer with id of 3
         */
        factory(Customer::class, 20)->create([
            'user_id' => 3
        ]);
        /**
         * create 20 customers for retailer with id of 4
         */
        factory(Customer::class, 10)->create([
            'user_id' => 4
        ]);


    }



}
