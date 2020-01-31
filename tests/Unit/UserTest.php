<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
   /** @test */
    public function a_user_has_orders() 
    {
        $user = factory('App\User')->create();
        $this->assertInstanceOf(Collection::class, $user->orders);
    }
}
