<?php
namespace Database\Seeders;

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        /**
         * two user are essential for system to work.
         * None is the default for new users with ID 0f 1
         * SystemAdmin is the main admin account with ID 0f 2
         * BuyerAdmin will be set for buyer admins
         * Retailer will be set for normal retailers
         */

        factory(Role::class)->create([
            'name' => 'None',
            'label' => 'Without Role'
        ]);

        factory(Role::class)->create([
            'name' => 'SystemAdmin',
            'label' => 'System Administrator'
        ]);

        factory(Role::class)->create([
            'name' => 'BuyerAdmin',
            'label' => 'Buyer Administrator'
        ]);

        factory(Role::class)->create([
            'name' => 'Retailer',
            'label' => 'Retailer'
        ]);
    }
}
