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
        factory(App\Role::class, 4)->create();

        //add SystemAdmin role to user
        $SystemAdminUser = App\User::find(1);
        $role = App\Role::where('name', 'SystemAdmin')->first();
        $SystemAdminUser->assignRole($role);

    }
}
