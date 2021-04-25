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
        /**
         * create SystemAdmin user
         */
        factory(User::class)->create([
            'name' => 'Hamed Afshar',
            'email' => 'abtin_bep@yahoo.com',
            'password' => Hash::make('123456789'),
            'confirmed' => 1,
            'locked' => 0,
            'language' => 'Persian',
            'tel' => '09123463474',
            'country' => 'Iran',
            'communication_media' => 'telegram'
        ]);

        /**
         * create BuyerAdmin user
         */
        factory(User::class)->create([
            'name' => 'Adil',
            'email' => 'hamed@yahoo.com',
            'password' => Hash::make('123456789'),
            'confirmed' => 1,
            'locked' => 0,
            'language' => 'Persian',
            'tel' => '09123463474',
            'country' => 'Iran',
            'communication_media' => 'telegram'
        ]);

        /**
         * create Retailer user
         */
        factory(User::class)->create([
            'name' => 'Amin',
            'email' => 'amin@yahoo.com',
            'password' => Hash::make('123456789'),
            'confirmed' => 1,
            'locked' => 0,
            'language' => 'Persian',
            'tel' => '09122035389',
            'country' => 'Iran',
            'communication_media' => 'telegram'
        ]);

        factory(User::class, 20)->create();

        /**
         * assign SystemAdmin role
         */
        $SystemAdminUser = User::find(1);
        $role = Role::where('name', 'SystemAdmin')->first();
        $role->changeRole($SystemAdminUser);

        /**
         * assign BuyerAdmin role
         */
        $BuyerAdminUser = User::find(2);
        $role = Role::where('name', 'BuyerAdmin')->first();
        $role->changeRole($BuyerAdminUser);

        /**
         * assign Retailer role
         */
        $Retailer = User::find(3);
        $role = Role::where('name', 'Retailer')->first();
        $role->changeRole($Retailer);
    }
}
