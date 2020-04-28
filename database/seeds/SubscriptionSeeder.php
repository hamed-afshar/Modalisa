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
        factory(App\Subscription::class, 3)->create()->each(function ($subscription) {
            for($i=0; $i<=3; $i++) {
                $subscription->users()->save(factory(App\User::class)->make());
            }
        });
    }
}
