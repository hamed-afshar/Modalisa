<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminFunctionsTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function SystemAdmin_can_access_to_admin_dashboard()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $this->get('/system-admin')->assertOk();
    }

    /** @test */
    public function SystemAdmin_can_access_to_security_center()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $this->get('/security-center')->assertStatus(200);
    }

    /** @test */
    public function SystemAdmin_can_access_user_center()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $this->get('user-center')->assertStatus(200);
    }

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
        $role = factory('App\Role')->create();
        $permission = factory('App\Permission')->create();
        $role->allowTo($permission);
        $this->assertDatabaseHas('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
    }

    /** @test */
    public function SystemAdmin_can_unassign_permission_to_role()
    {
        $role = factory('App\Role')->create();
        $permission = factory('App\Permission')->create();
        $role->disAllowTo($permission);
        $this->assertDatabaseMissing('role_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);
    }

    /** @test */
    public function SystemAdmin_can_assign_subscription_to_user()
    {
        $user = factory('App\User')->create();
        $subscription = factory('App\Subscription')->create();
        $subscription->assignUser($user);
        $this->assertDatabaseHas('users', ['subscription_id' => $subscription->id]);
    }


    /** @test */
    public function SystemAdmin_can_confirm_and_unlock_users()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $newUser = factory('App\User')->create();
        $this->patch($newUser->path(), [
            'confirmed' => 1,
            'locked' => 0
        ]);;
        $this->assertEquals(1, User::where('id', $newUser->id)->value('confirmed'));
        $this->assertEquals(0, User::where('id', $newUser->id)->value('locked'));

    }

    /** @test */
    public function only_SystemAdmin_can_confirm_transactions()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $newUser = factory('App\User')->create();
        $transaction = factory('App\Transaction')->create(['user_id' => $newUser->id]);
        $this->get('/transactions/confirm/' . $transaction->id);
        $this->assertEquals(1, Transaction::where('id', $transaction->id)->value('confirmed'));
    }

}
