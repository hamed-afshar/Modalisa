<?php

namespace Database\Seeders;

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Permission::class)->create([
           'name' => 'see-histories',
           'label' => 'see histories'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-histories',
            'label' => 'create histories'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-histories',
            'label' => 'delete histories'
        ]);

        factory(Permission::class)->create([
            'name' => 'see-costs',
            'label' => 'see costs'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-costs',
            'label' => 'create costs'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-costs',
            'label' => 'delete costs'
        ]);

        factory(Permission::class)->create([
            'name' => 'see-kargos',
            'label' => 'see kargos'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-kargos',
            'label' => 'create kargos'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-kargos',
            'label' => 'delete kargos'
        ]);

        factory(Permission::class)->create([
            'name' => 'see-transactions',
            'label' => 'see transactions'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-transactions',
            'label' => 'create transactions'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-transactions',
            'label' => 'delete transactions'
        ]);

        factory(Permission::class)->create([
            'name' => 'see-customers',
            'label' => 'see customers'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-customers',
            'label' => 'create customers'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-customers',
            'label' => 'delete customers'
        ]);

        factory(Permission::class)->create([
            'name' => 'see-notes',
            'label' => 'see notes'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-notes',
            'label' => 'create notes'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-notes',
            'label' => 'delete notes'
        ]);

        factory(Permission::class)->create([
            'name' => 'see-images',
            'label' => 'see images'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-images',
            'label' => 'create images'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-images',
            'label' => 'delete images'
        ]);

        factory(Permission::class)->create([
            'name' => 'see-orders',
            'label' => 'see orders'
        ]);

        factory(Permission::class)->create([
            'name' => 'create-orders',
            'label' => 'create orders'
        ]);

        factory(Permission::class)->create([
            'name' => 'delete-orders',
            'label' => 'delete orders'
        ]);




    }
}
