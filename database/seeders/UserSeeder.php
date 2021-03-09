<?php
namespace Database\Seeders;

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        /*
         * create SystemAdmin user first
         */
        factory(User::class)->create([
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
         * assign SystemAdmin role to user
         */
        $SystemAdminUser = User::find(1);
        $role = Role::where('name', 'SystemAdmin')->first();
        $role->changeRole($SystemAdminUser);
    }
}
