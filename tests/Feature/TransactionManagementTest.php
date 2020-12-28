<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransactionManagementTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    /** @test
     * make sure that retailers can not change or access other retailers transactions
     * for all actions including update,delete
     */
    public function retailers_only_can_access_to_their_own_resources()
    {
        factory('App\Subscription')->create();
        $role = factory('App\Role')->create(['name' => 'retailer']);
        $permission = factory('App\Permission')->create(['name' => 'make-payment']);
        $user = factory('App\User')->create(['confirmed' => 1, 'locked' => 0]);
        $newUser = factory('App\User')->create(['confirmed' => 1, 'locked' => 0]);
        $role->changeRole($user);
        $role->changeRole($newUser);
        $role->allowTo($permission);
        $transaction1 = factory('App\Transaction')->create(['user_id' => $user->id]);
        $newAttributes = [
            'currency' => 'USD',
            'amount' => '9999',
            'pic' => 'new_link',
            'comment' => 'new comment'
        ];
        $this->actingAs($newUser);
        $this->get($transaction1->path())->assertForbidden();
        $this->patch($transaction1->path(), $newAttributes)->assertForbidden();
        $this->delete($transaction1->path(), $newAttributes)->assertForbidden();
    }

    /** @test
     * retailers can not make any changes to confirmed transactions
     * including edit and delete
     */
    public function retailer_can_not_delete_or_update_confirmed_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id, 'confirmed' => 1]);
        $this->patch($transaction->path())->assertForbidden();
        $this->delete($transaction->path())->assertForbidden();
    }

    /** @test
     * retailers can not confirm the transactions
     * only SystemAdmin is able to confirm transactions
     */
    public function retailers_can_not_confirm_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $newAttributes = [
            'confirmed' => 1
        ];
        $this->patch('/transactions/confirm/' . $transaction->id, $newAttributes)->assertForbidden();
    }

    /** @test */
    public function retailers_can_see_their_transactions()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->get('/transactions')->assertSeeText($transaction->pic);
    }


    /**
     * this should be tested in VueJs
     */

    public function form_is_available_to_create_a_transaction()
    {

    }

    /** @test */
    public function retailers_can_create_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id]);
        $this->post('/transactions', $attributes);
        $this->assertDatabaseHas('transactions', ['comment' => $attributes['comment']]);
    }

    /** @test */
    public function currency_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'currency' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('currency');
    }

    /** @test */
    public function amount_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'amount' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('amount');
    }

    /** @test */
    public function comment_is_required()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'comment' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('comment');
    }

    /** @test
     * test image upload functionality on transaction creation time separately here
     */
    public function image_can_be_uploaded_on_transaction_creation()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $attributes = factory('App\Transaction')->raw(
            [
                'user_id' => Auth::user()->id,
                'image' => UploadedFile::fake()->create('pic.jpg')
            ]);
        $this->post('/transactions', $attributes);
        $transaction = Transaction::find(1);
        $image = $transaction->images()->find($transaction->id);
        $image_name = $image->image_name;
        // Assert file exist on server
        $this->assertFileExists(public_path('storage' . $image_name));
        // Assert database has image which has a imagable_id for created transaction
        $this->assertDatabaseHas('images', ['imagable_id' => $transaction->id]);
    }


    /** @test */
    public function retailer_can_see_a_single_transaction()
    {
        $this->prepNormalEnv('retailers', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->get($transaction->path())->assertSeeText($transaction->comment);
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_a_transaction()
    {

    }

    /** @test
     * Users only are able to update not confirmed transactions
     */
    public function retailer_can_update_not_confirmed_transactions()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['make-payment'], 0, 1);
        // create a transaction
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        // create image for this transaction
        factory('App\Image')->create([
            'user_id'=>Auth::user()->id,
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id,
            'image_name' => '/images/transaction1.jpg'
        ]);
        // save old image name
        $imageName = $transaction->images()->where('imagable_id', $transaction->id);
        $oldImageName = $imageName->get('image_name');
        // create a fake image to be added to updating attributes
        $newPic = UploadedFile::fake()->create('newPic.jpg');
        // creating two types of attributes to test updates with or without images
        $newAttributesWithImage = [
            'currency' => 'USD',
            'amount' => '9999',
            'comment' => 'new comment1',
            'image' => $newPic
        ];
        $newAttributesWithoutImage = [
            'currency' => 'USD',
            'amount' => '5555',
            'comment' => 'new comment2',
        ];
        // update with new image and assert to see new image file existence on server and record in db
        $this->patch($transaction->path(), $newAttributesWithImage);
        $this->assertDatabaseHas('transactions', ['comment' => $newAttributesWithImage['comment']]);
        $transaction = Transaction::find(1);
        $this->assertFileExists(public_path('storage' . $transaction->image_name));
        // update without new image and assert to see new image file existence on server and record in db
        // old image also must be deleted
        $this->patch($transaction->path(), $newAttributesWithoutImage);
        $this->assertDatabaseHas('transactions', ['comment' => $newAttributesWithoutImage['comment']]);
        $this->assertFileNotExists(public_path('storage' . $oldImageName));
    }

    /** @test */
    public function retailer_can_delete_not_confirmed_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->delete($transaction->path());
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    /** @test */
    public function transaction_belongs_to_a_user()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $transaction->user);
    }

    /** @test */
    public function user_can_have_many_transactions()
    {
        $this->prepNormalEnv('retailer', 'make-payment', 0, 1);
        $user = Auth::user();
        factory('App\Transaction')->create(['user_id' => $user->id]);
        $transaction = $user->transactions->find(1);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /** @test */
    public function guests_can_not_access_transaction_management()
    {
        $this->get('/transactions')->assertRedirect('login');
        $this->get('/transactions/create')->assertRedirect('login');
        $this->post('/transactions')->assertRedirect('login');
        $this->get('/transactions/1')->assertRedirect('login');
        $this->get('/transactions/1' . '/edit')->assertRedirect('login');
        $this->patch('/transactions/1')->assertRedirect('login');
        $this->delete('/transactions/1')->assertRedirect('login');
    }
}
