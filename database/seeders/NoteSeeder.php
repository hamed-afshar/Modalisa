<?php

namespace Database\Seeders;

use App\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * create note for retailer with id of 3
         * all notes are related to the order number 1
         */
        factory(Note::class, 10)->create([
            'user_id' => 3,
            'notable_type' => 'App\Order',
            'notable_id' => 1
        ]);

        /**
         * create note for user with id of 4
         * all notes are related to the order number 100
         */
        factory(Note::class, 30)->create([
            'user_id' => 4,
            'notable_type' => 'App\Order',
            'notable_id' => 100
        ]);
    }
}
