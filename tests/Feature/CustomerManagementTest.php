<?php

namespace Tests\Feature;

use App\Customer;
use App\Order;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test
     * users should have see-customers permission to be allowed
     */
    public function retailers_can_see_its_own_customers()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $this->get('/customers')->assertSeeText($customer->name);
        // users can only see their own records
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $this->get('/customers')->assertDontSeeText($customer->name);
    }

    /** this should be tested in VueJs */
    public function form_is_available_to_create_a_customer()
    {

    }

    /** @test
     * users should have create-customers permission to be allowed
     */
    public function retailers_can_create_customer()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $attributes = factory('App\Customer')->raw(['user_id' => Auth::user()->id]);
        $this->post('/customers', $attributes);
        $this->assertDatabaseHas('customers', ['name' => $attributes['name']]);
    }

    /** @test */
    public function name_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $attributes = factory('App\Customer')->raw(['user_id'=>Auth::user()->id, 'name' => '']);
        $this->post('/customers', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function tel_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $attributes = factory('App\Customer')->raw(['user_id'=>Auth::user()->id, 'tel' => '']);
        $this->post('/customers', $attributes)->assertSessionHasErrors('tel');
    }

    /** @test */
    public function communication_media_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $attributes = factory('App\Customer')->raw(['user_id'=>Auth::user()->id, 'communication_media' => '']);
        $this->post('/customers', $attributes)->assertSessionHasErrors('communication_media');
    }

    /** @test */
    public function communication_id_is_required()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $attributes = factory('App\Customer')->raw(['user_id'=>Auth::user()->id, 'communication_id' => '']);
        $this->post('/customers', $attributes)->assertSessionHasErrors('communication_id');
    }

    /** @test */
    public function retailer_can_see_a_single_customer()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $this->get($customer->path())->assertSeeText($customer->name);
        // users can only see their own records
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $this->get($customer->path())->assertForbidden();
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_customers()
    {

    }

    /** @test
     * users should have create-customers permission to be allowed
     */
    public function retailers_can_update_customers()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers'], 0 , 1);
        $customer = factory('App\Customer')->create(['user_id' => Auth::user()->id]);
        $newAttributes = [
            'name' => 'new Name',
            'tel' => 'new tel',
            'communication_media' => 'whatsapp',
            'communication_id' => 'new id',
            'address' => 'new address',
            'email' => 'new email'
        ];
        $this->patch($customer->path(), $newAttributes);
        $this->assertDatabaseHas('customers', $newAttributes);
        //users can only update their own records
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $this->patch($customer->path(), $newAttributes)->assertForbidden();
    }

    /** @test
     * users should have delete-customers permission to be allowed
     * users can only delete their own records
     */
    public function retailers_can_delete_customers()
    {
        $this->prepNormalEnv('retailer', ['see-customers', 'create-customers', 'delete-customers'], 0 , 1);
        $retailer1 = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $retailer1->id]);
        // users can not delete records belong to others
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers', 'delete-customers'], 0 , 1);
        $retailer2 = Auth::user();
        $this->delete($customer->path())->assertForbidden();
        // users can delete their own records
        $this->actingAs($retailer1);
        $this->delete($customer->path());
        $this->assertDatabaseMissing('customers', ['id'=>$customer->id]);
    }

    /** @test
     * one to many relationship
     */
    public function each_user_has_many_customers()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $user = Auth::user();
        factory('App\Customer')->create(['user_id' => $user->id]);
        $this->assertInstanceOf(Customer::class, $user->customers->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_customer_belongs_to_a_user()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $user = Auth::user();
        $customer = factory('App\Customer')->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $customer->user);
    }

    /** @test
     * one to many relationship
     */
    public function each_customer_has_many_orders()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $this->prepOrder(1,0);
        $customer = Customer::find(1);
        $this->assertInstanceOf(Order::class, $customer->orders->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_order_belongs_to_a_customer()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer2', ['see-customers', 'create-customers'], 0 , 1);
        $this->prepOrder(1, 0);
        $order = Order::find(1);
        $this->assertInstanceOf(Customer::class, $order->customer);
    }

}
