<?php

namespace Tests\Feature;

use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_create_role()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Role')->raw();
        $this->post('/role', $attributes);
        $this->assertDatabaseHas('roles', $attributes);
    }

    /** @test */
    public function guests_can_not_access_role_management()
    {
        $this->post('/role')->assertRedirect('login');
    }
}
