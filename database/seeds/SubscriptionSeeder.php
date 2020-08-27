<?php

use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * create a based subscription which is default for all new users
         */
        factory(App\Subscription::class)->create([
            'plan' => 'Basic',
            'cost_percentage' => 30
        ]);
    }
}
