<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * two user are essential for system to work.
         * SystemAdmin is the main admin account with ID 0f 2
         * None is the default for new users with ID 0f 1
         */
        factory(App\Role::class)->create([
            'name' => 'None',
            'label' => 'Without Role'
        ]);

        factory(App\Role::class)->create([
            'name' => 'SystemAdmin',
            'label' => 'System Administrator'
        ]);



    }
}
