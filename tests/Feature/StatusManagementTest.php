<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StatusManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test  */
    public function onlySystemAdmin_can_see_all_statuses()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $status = factory('App\Status')->create();
        $this->get('api/statuses')->assertSeeText($status->name);
        //other users can not see statuses
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get('api/statuses')->assertForbidden();
    }

    /** @test */
    public function SystemAdmin_can_create_status()
    {
        $this->prepAdminEnv('SystemAdmin', 0,1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $newAttributes = factory('App\Status')->raw();
        $this->post('api/statuses', $newAttributes);
        $this->assertDatabaseHas('statuses', $newAttributes);
        //other users are not allowed to create statuses
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->post('api/statuses', $newAttributes)->assertForbidden();
    }

    /** @test  */
    public function name_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0 ,1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $newAttributes = factory('App\Status')->raw(['name' => '']);
        $this->post('api/statuses', $newAttributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function description_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $newAttributes = factory('App\Status')->raw(['description' => '']);
        $this->post('api/statuses', $newAttributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function priority_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $newAttributes = factory('App\Status')->raw(['priority' => '']);
        $this->post('api/statuses', $newAttributes)->assertSessionHasErrors('priority');
    }

    /** @test */
    public function only_SystemAdmin_can_view_a_single_status()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $status = factory('App\Status')->create();
        $this->get($status->path())->assertSeeText($status->description);
        //other users are not allowed to see a single status
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->get($status->path())->assertForbidden();
    }

    /** @test */
    public function only_SystemAdmin_can_update_status()
    {
        $this->prepAdminEnv('SystemAdmin', 0 , 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($SystemAdmin, 'api');
        $status = factory('App\Status')->create();
        $newAttributes = [
            'priority'=>'1',
            'name'=>'New Name',
            'description' => 'New Description'
        ];
        $this->patch($status->path(), $newAttributes);
        $this->assertDatabaseHas('statuses', $newAttributes);
        //other users are not allowed to update statuses
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->patch($status->path(), $newAttributes)->assertForbidden();
    }

    /** @test */
    public function only_SystemAdmin_can_delete_status()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $status = factory('App\Status')->create();
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        //other users can not delete statuses
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->delete($status->path())->assertForbidden();
        //SystemAdmin can delete statuses
        $this->actingAs($SystemAdmin, 'api');
        $this->delete($status->path());
        $this->assertDatabaseMissing('statuses', ['id' => $status->id]);
    }


    /** @test */
    public function guests_can_not_access_permission_management()
    {
        $status = factory('App\Status')->create();
        $this->get('api/statuses')->assertRedirect('login');
        $this->post('api/statuses')->assertRedirect('login');
        $this->get($status->path())->assertRedirect('login');
        $this->patch($status->path())->assertRedirect('login');
        $this->delete($status->path())->assertRedirect('login');
    }


}
