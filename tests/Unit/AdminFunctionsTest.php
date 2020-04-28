<?php

namespace Tests\Feature;

use App\AccessProvider;
use App\Permission;
use App\Role;
use App\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminFunctionsTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function SystemAdmin_can_assign_a_role_to_user()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create();
        $user->assignRole($role);
        $this->assertDatabaseHas('user_roles', ['user_id' => $user->id, 'role_id' => $role->id]);
    }

    /** @test */
    public function SystemAdmin_can_assign_permission_to_role()
    {
        $this->withoutExceptionHandling();
        $role = factory('App\Role')->create();
        $permission = factory('App\Permission')->create();
        $role->allowTo($permission);
        $this->assertDatabaseHas('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
    }

    /** @test */
    public function SystemAdmin_can_assign_subscription_to_user()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create();
        $subscription = factory('App\Subscription')->create();
        $subscription->assignUser($user);
        $this->assertDatabaseHas('users', ['subscription_id' => $subscription->id]);
    }

    /** @test */
    public function form_is_available_to_edit_users()
    {

    }

    /** @test */
    public function SystemAdmin_can_confirm_or_lock_users()
    {

    }

    /** @test */
    public function SystemAdmin_can_delete_users()
    {

    }

    /** @test */
    public function not_confirmed_users_can_not_access_system()
    {

    }

    /** @test */
    public function locked_users_can_not_access_system()
    {

    }

}
