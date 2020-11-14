<?php

namespace Tests\Feature;

use App\Product;
use App\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatusManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test
     * only SystemAdmin can make changes to the statuses
     * other users are not allowed to make any changes including
     * index, create, store, show, update and delete
     */
    public function other_users_can_not_make_changes_to_the_statuses()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0 , 1);
        $status = factory('App\Status')->create();
        $newAttributes = factory('App\Status')->raw();
        $this->get('/statuses')->assertForbidden();
        $this->get('/statuses/create')->assertForbidden();
        $this->post('/statuses', $newAttributes)->assertForbidden();
        $this->get($status->path())->assertForbidden();
        $this->get($status->path() . '/edit')->assertForbidden();
        $this->patch($status->path(), $newAttributes)->assertForbidden();
        $this->delete($status->path())->assertForbidden();
    }

    /** @test */
    public function only_BuyerAdmin_can_see_all_statuses()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $status = factory('App\Status')->create();
        $this->get('/statuses')->assertSeeText($status->name);

    }

    /*
     * This should be tested in VueJs
     */
    public function form_is_available_to_create_status()
    {

    }

    /** @test */
    public function SystemAdmin_can_create_status()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0,1);
        $newAttributes = factory('App\Status')->raw();
        $this->post('/statuses', $newAttributes);
        $this->assertDatabaseHas('statuses', $newAttributes);
    }

    /** @test  */
    public function name_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0 ,1);
        $newAttributes = factory('App\Status')->raw(['name' => '']);
        $this->post('/statuses', $newAttributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function description_is_required()
    {
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $newAttributes = factory('App\Status')->raw(['description' => '']);
        $this->post('/statuses', $newAttributes)->assertSessionHasErrors('description');
    }

    /*
     * This test is not neccessary
     */
    public function only_system_admin_can_view_a_single_status()
    {

    }

    /*
     * This should be tested in VueJs
     */
    public function form_is_available_to_update_status()
    {

    }

    /** @test */
    public function only_SystemAdmin_can_update_status()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0 , 1);
        $status = factory('App\Status')->create();
        $newAttributes = [
            'name'=>'New Name',
            'description' => 'New Description'
        ];
        $this->patch($status->path(), $newAttributes);
        $this->assertDatabaseHas('statuses', $newAttributes);
    }

    /** @test */
    public function only_SystemAdmin_can_delete_status()
    {
        $this->withoutExceptionHandling();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $status = factory('App\Status')->create();
        $this->delete($status->path());
        $this->assertDatabaseMissing('statuses', ['id' => $status->id]);
    }

    /** @test
     * one to many relationship
     */
    public function status_can_have_many_products()
    {
        $status = factory('App\Status')->create();
        factory('App\Product')->create(['status_id' => $status->id]);
        $product = $status->products->find(1);
        $this->assertInstanceOf(Product::class, $product);
    }

    /** @test
     * one to many relationship
     */
    public function product_belongs_to_a_status()
    {
        $this->withoutExceptionHandling();
        $status = factory('App\Status')->create();
        $product = factory('App\Product')->create(['status_id' => $status->id]);
        $this->assertInstanceOf(Status::class, $product->status);
    }

    /** @test */
    public function guests_can_not_access_permission_management()
    {
        $status = factory('App\Status')->create();
        $this->get('/statuses')->assertRedirect('login');
        $this->get('/statuses/create')->assertRedirect('login');
        $this->post('/statuses')->assertRedirect('login');
        $this->get($status->path())->assertRedirect('login');
        $this->get($status->path() . '/edit')->assertRedirect('login');
        $this->patch($status->path())->assertRedirect('login');
        $this->delete($status->path())->assertRedirect('login');
    }


}
