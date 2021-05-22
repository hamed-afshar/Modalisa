<?php

namespace Tests\Feature;

use App\History;
use App\Product;
use App\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class HistoryManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test
     * users can see histories for the given product
     * users should have see-histories permission to be allowed
     */
    public function retailers_can_check_their_product_histories()
    {
        $this->prepNormalEnv('retailer', ['see-histories','create-orders'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->prepOrder(1,0);
        $status1 = Status::find(1);
        $product = Product::find(1);
        $status2 = factory('App\Status')->create([
            'name' => 'arrived',
            'description' => 'arrived to office'
        ]);
        $history1 = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status1->id,
        ]);
        $history2 = factory('App\History')->create([
            'product_id' => $product->id,
            'status_id' => $status2->id,
        ]);
        $this->get('api/histories/' . $product->id)->assertSeeText($history1->status_id)
            ->assertSeeText($history2->status_id);
        // users can only see their own records
        $this->prepNormalEnv('retailer2', ['see-histories','create-orders'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->get('api/histories/' . $product->id)->assertDontSeeText($history1->status_id)
        ->assertDontSeeText($history2->status_id);
    }

    /** @test
     * ProductObserver is responsible to create history on order creation time
     */
    public function product_model_observes_to_create_history_on_product_creation()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-orders', 'see-orders'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->prepOrder(1,0);
        $product = Product::find(1);
        $status = Status::find(2);
        $this->assertDatabaseHas('histories', ['product_id' => $product->id, 'status_id' => $status->id]);
    }

    /** @test
     * only users with privilege permissions are allowed to create histories
     */
    public function only_privilege_users_can_create_history()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('BuyerAdmin', ['create-histories', 'see-histories'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->actingAs($BuyerAdmin, 'api');
        $this->prepOrder(1,0);
        $status = factory('App\Status')->create([
                'description' => 'in-office'
        ]);

        $product = Product::find(1);
        $attributes = [
          'status_id' => $status->id,
          'product_id' => $product->id
        ];
        $this->post('api/histories', $attributes );
        $this->assertDatabaseHas('histories', $attributes);
        //other users are not allowed to create history
        $this->prepNormalEnv('retailer', ['create-transactions', 'see-histories'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $this->post('api/histories' , $attributes)->assertForbidden();
    }

    /** @test
     * only users with privilege permissions are allowed to delete histories
     */
    public function only_super_privilege_users_can_delete_histories()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-histories', 'see-histories', 'delete-histories'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepOrder(1,0);
        $history = History::find(1);
        //other users are not allowed to delete records
        $this->prepNormalEnv('retailer', ['create-histories', 'see-histories', 'delete-histories'], 0, 1);
        $this->delete($history->path())->assertForbidden();
        //BuyerAdmin can delete histories
        $this->actingAs($BuyerAdmin);
        $this->delete($history->path());
        $this->assertDatabaseMissing('histories', ['id' => $history->id]);

    }

    /** @test
     * one to many relationship
     */
    public function each_status_has_many_histories()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['make-order', 'see-orders'], 0, 1);
        $this->prepOrder(1,0);
        $status = Status::find(2);
        // History automatically is always created on order creation
        $this->assertInstanceOf(History::class, $status->histories->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_history_belongs_to_a_status()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['make-order', 'see-orders'], 0, 1);
        $this->prepOrder(1,0);
        // History automatically is always created on order creation
        $history = History::find(1);
        $this->assertInstanceOf(Status::class, $history->status);
    }

    /** @test
     * one to many relationship
     */
    public function each_product_has_many_histories()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['make-order', 'see-orders'], 0, 1);
        $this->prepOrder(1,0);
        $product = Product::find(1);
        $this->assertInstanceOf(History::class, $product->histories->find(1));
    }

    /** @test
     * one to many relationship
     */
    public function each_history_belongs_to_a_product()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['make-order', 'see-orders'], 0, 1);
        $this->prepOrder(1,0);
        $history = History::find(1);
        $this->assertInstanceOf(Product::class, $history->product);
    }

    /** @test */
    public function guests_can_not_access_history_management()
    {
        $this->get('/histories/1')->assertRedirect('login');
        $this->post('/histories/')->assertRedirect('login');
        $this->delete('/histories/1')->assertRedirect('login');
    }

}
