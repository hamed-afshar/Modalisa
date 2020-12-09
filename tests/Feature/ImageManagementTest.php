<?php

namespace Tests\Feature;

use App\Image;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ImageManagementTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    /** @test
     * one to many relationship
     */
    public function each_user_have_many_images()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create([
            'user_id' => $user->id,
            'image_name' => 'test.jpg'
        ]);
        factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => $transaction->image_name,
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id
        ]);
        $this->assertInstanceOf(Image::class, $user->images->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_image_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-order', 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create([
            'user_id' => $user->id,
            'image_name' => 'test.jpg'
        ]);
        $image = factory('App\Image')->create([
            'user_id' => $user->id,
            'image_name' => $transaction->image_name,
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id
        ]);
        $this->assertInstanceOf(User::class, $image->user);
    }

    /** @test
     * polymorphic one to many relationship
     */
    public function transactions_may_have_many_images()
    {

    }
}
