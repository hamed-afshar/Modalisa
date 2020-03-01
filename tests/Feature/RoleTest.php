<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{

    /** @test */
    public function only_system_admin_can_create_role()
    {
        $this->withoutExceptionHandling();
        $attributes = factory('App\Role')->raw();
        $this->post("/role", $attributes);
        $this->assertDatabaseHas($attributes);
    }

    /** @test */
    public function guests_can_not_access_role_management()
    {
        $this->post('/role')->assertRedirect('login');
    }
}
