<?php

namespace Database\Seeders;

use App\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * create image for retailer with id of 3
         */
        for ($i = 1; $i <= 50; $i++) {
            factory(Image::class)->create([
                'user_Id' => 3,
                'image_name' => '/images/product-image.jpg',
                'imagable_type' => 'App\Product',
                'imagable_id' => $i
            ]);
        }
    }
}
