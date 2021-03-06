<?php
namespace Database\Seeders;

use App\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        /*
         * create basic subscription which is default for all new users
         */
        factory(Subscription::class)->create([
            'plan' => 'Basic',
            'cost_percentage' => 30
        ]);
    }
}
