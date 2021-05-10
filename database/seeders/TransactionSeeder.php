<?php

namespace Database\Seeders;

use App\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**  Create transaction for user with id of 3 */
        factory(Transaction::class, 10)->create([
            'user_id' => 3
        ]);
        /**  Create transaction for user with id of 4 */
        factory(Transaction::class, 20)->create([
            'user_id' => 4
        ]);
    }
}
