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
        factory(App\User::class)->create()->each(function ($user) {
            $user->role()->save(factory(App\Role::class)->make(['id' => 1, 'name' => 'SystemAdmin']));
        });
    }
}
