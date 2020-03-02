<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccessManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function only_SystemAdmin_can_create_role()
    {
        $this->withoutExceptionHandling();
        $userRole = factory('App\UserRole')->create();
        $user = User::find($userRole->user_id);
        $this->actingAs($user);
        $rolePermission = factory('App\RolePermission')->create();
        $attributes = factory('App\Role')->raw();
        $this->post('/roles', $attributes);
        $this->assertDatabaseHas('roles', $attributes);
    }


    /** @test */
    public function only_SystemAdmin_can_view_roles()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());
        $role = factory('App\Role')->create();
        $this->get('/roles')->assertSee($role->name);
    }

    /** @test */
    public function guests_can_not_access_role_management()
    {
        $this->post('/role')->assertRedirect('login');
    }
}
