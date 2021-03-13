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

        factory(Subscription::class)->create([
            'plan' => 'Basic',
            'cost_percentage' => 10
        ]);

        factory(Subscription::class)->create([
            'plan' => 'Gold',
            'cost_percentage' => 5
        ]);

        factory(Subscription::class)->create([
            'plan' => 'Platinum',
            'cost_percentage' => 0
        ]);

    }
}
