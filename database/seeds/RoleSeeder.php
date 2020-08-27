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
        $SystemAdminUser->assignRole($role);

    }
}
