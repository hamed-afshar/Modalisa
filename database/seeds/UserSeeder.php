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
        /*
         * create SystemAdmin user first
         */
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

        /*
         * create roles in db
        */
        factory(App\Role::class)->create([
            'name' => 'SystemAdmin',
            'label' => 'System Administrator'
        ]);

        /*
         * assign SystemAdmin role to user
         */
        $SystemAdminUser = App\User::find(1);
        $role = App\Role::where('name', 'SystemAdmin')->first();
        $role->changeRole($SystemAdminUser);
    }
}
