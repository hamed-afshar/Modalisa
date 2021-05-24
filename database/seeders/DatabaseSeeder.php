<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Database\Seeders\RoleSeeder::class);
        $this->call(\Database\Seeders\PermissionSeeder::class);
        $this->call(\Database\Seeders\SubscriptionSeeder::class);
        $this->call(\Database\Seeders\UserSeeder::class);
        $this->call(\Database\Seeders\CustomerSeeder::class);
        $this->call(\Database\Seeders\StatusSeeder::class);
        $this->call(\Database\Seeders\ProductSeeder::class);
        $this->call(\Database\Seeders\KargoSeeder::class);
        $this->call(\Database\Seeders\TransactionSeeder::class);
        $this->call(\Database\Seeders\NoteSeeder::class);
        $this->call(\Database\Seeders\CostSeeder::class);
    }
}
