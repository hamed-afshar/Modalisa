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
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['see-histories','create-orders'], 0, 1);
        $this->prepOrder();
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
        $this->get('/histories/' . $product->id)->assertSeeText($history1->created_at)
            ->assertSeeText($history2->created_at);
        // users can only see their own records
        $this->prepNormalEnv('retailer2', ['see-histories','create-orders'], 0, 1);
        $this->get('/histories/' . $product->id)->assertDontSeeText($history1->created_at)
        ->assertDontSeeText($history2->created_at);
    }

    /** @test
     * ProductObserver is responsible to create history on order creation time
     */
    public function product_model_observes_to_create_history_on_product_creation()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['make-order', 'see-orders'], 0, 1);
        $this->prepOrder();
        $product = Product::find(1);
        $status = Status::find(1);
        $this->assertDatabaseHas('histories', ['product_id' => $product->id, 'status_id' => $status->id]);
    }

    /** @test
     * only BuyerAdmins and users with privilege permissions are allowed to create histories
     */
    public function only_BuyerAdmin_can_create_history()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-histories', 'see-histories'], 0, 1);
        $this->prepOrder();
        $status = factory('App\Status')->create([
                'description' => 'in-office'
        ]);

        $product = Product::find(1);
        $attributes = [
          'status_id' => $status->id,
          'product_id' => $product->id
        ];
        $this->post('/histories', $attributes );
        $this->assertDatabaseHas('histories', $attributes);
        //other users are not allowed to create history
        $this->prepNormalEnv('retailer', ['create-transactions', 'see-histories'], 0, 1);
        $this->post('/histories' , $attributes)->assertForbidden();
    }

    /** @test
     * only BuyerAdmins and users with privilege permissions are allowed to delete histories
     */
    public function only_BuyerAdmin_can_delete_histories()
    {
        $this->prepNormalEnv('BuyerAdmin', ['create-histories', 'see-histories', 'delete-histories'], 0, 1);
        $BuyerAdmin = Auth::user();
        $this->prepOrder();
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
        $this->prepOrder();
        $status = Status::find(1);
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
        $this->prepOrder();
        $status = Status::find(1);
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
        $this->prepOrder();
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
        $this->prepOrder();
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
