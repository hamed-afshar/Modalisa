<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create SystemAdmin user first
        factory(App\User::class)->create([
            'name' => 'Hamed Afshar',
            'email' => 'abtin_bep@yahoo.com',
            'password' => Hash::make('13651362'),
            'confirmed' => 1,
            'locked' => 0,
            'language' => 'Persian',
            'tel' => '09123463474',
            'country' => 'Iran',
            'communication_media' => 'telegram'
        ]);
        //create all subscriptions and assign users to each
        factory(App\Subscription::class, 3)->create()->each(function ($subscription) {
            for($i=0; $i<=3; $i++) {
                $subscription->users()->save(factory(App\User::class)->make());
            }
        });
    }
}
