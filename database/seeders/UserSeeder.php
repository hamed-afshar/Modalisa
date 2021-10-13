<?php
namespace Database\Seeders;

use App\Role;
use App\Subscription;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
         * create Retailer1 user
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

        /**
         * create Retailer2 user
         */
        factory(User::class)->create([
            'name' => 'Mohammad',
            'email' => 'Mohammad@yahoo.com',
            'password' => Hash::make('123456789'),
            'confirmed' => 1,
            'locked' => 0,
            'language' => 'Persian',
            'tel' => '09124445566',
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
        $Retailer1 = User::find(3);
        $Retailer2 = User::find(4);

        //assign required permissions to the role
        $retailerRole = Role::where('name', 'Retailer')->first();
        $buyerAdminRole = Role::where('name', 'BuyerAdmin')->first();
        $permissionsArray =[
            'see-transactions',
            'create-transactions',
            'delete-transactions',
            'see-customers',
            'create-customers',
            'delete-customers',
            'see-notes',
            'create-notes',
            'delete-notes',
            'see-images',
            'create-images',
            'delete-images',
            'see-histories',
            'create-histories',
            'see-costs',
            'see-kargos',
            'create-kargos',
            'delete-kargos',
            'create-orders',
            'see-orders',
            'delete-orders'
        ];
        foreach ($permissionsArray as $permission) {
            $permissionID = DB::table('permissions')->where('name', '=', $permission)->value('id');
            $retailerRole->allowTo($permissionID);
            $buyerAdminRole->allowTo($permissionID);
        }
        $role->changeRole($Retailer1);
        $role->changeRole($Retailer2);
        //assign subscription to the retailer1 and retailer2
        $subscription = Subscription::find(1);
        $subscription->changeSubscription($Retailer1);
        $subscription->changeSubscription($Retailer2);
    }
}
