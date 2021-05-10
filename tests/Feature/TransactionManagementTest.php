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


    /** @test */
    public function retailers_can_see_their_own_transactions()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer1', ['see-transactions', 'see-costs'], 0, 1);
        $retailer1 = Auth::user();
        $this->actingAs($retailer1,'api');
        $transaction = factory('App\Transaction')->create(['user_id' => $retailer1->id]);
        $this->get('api/transactions')->assertSeeText($transaction->comment);
        //users are only able to see their own transactions
        $this->prepNormalEnv('retailer2', ['see-transactions', 'see-costs'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->get('api/transactions')->assertDontSeeText($transaction->comment);
    }


    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_create_a_transaction()
    {

    }

    /**
     * @test
     * user should have create-transactions permission to be allowed
     */
    public function retailers_can_create_transaction()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-transactions', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id]);
        $this->post('api/transactions', $attributes);
        $this->assertDatabaseHas('transactions', $attributes);
    }

    /** @test */
    public function currency_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-transactions', 'see-costs'], 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'currency' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('currency');
    }

    /** @test */
    public function amount_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-transactions', 'see-costs'], 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'amount' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('amount');
    }

    /** @test */
    public function comment_is_required()
    {
        $this->prepNormalEnv('retailer', ['create-transactions', 'see-costs'], 0, 1);
        $attributes = factory('App\Transaction')->raw(['user_id' => Auth::user()->id, 'comment' => '']);
        $this->post('/transactions', $attributes)->assertSessionHasErrors('comment');
    }

    /** @test
     * test image upload functionality on transaction creation time separately here
     */
    public function image_can_be_uploaded_on_transaction_creation()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer', ['create-transactions', 'see-costs'], 0, 1);
        $retailer1 = Auth::user();
        $this->actingAs($retailer1, 'api');
        $attributes = factory('App\Transaction')->raw(
            [
                'user_id' => Auth::user()->id,
                'image' => UploadedFile::fake()->create('pic.jpg')
            ]);
        $this->post('api/transactions', $attributes);
        $transaction = Transaction::find(1);
        $image = $transaction->images()->find($transaction->id);
        $image_name = $image->image_name;
        // Assert file exist on server
        $this->assertFileExists(public_path('storage' . $image_name));
        // Assert database has image which has a imagable_id for created transaction
        $this->assertDatabaseHas('images', ['user_id' =>Auth::user()->id, 'image_name' => $image_name,'imagable_type' => 'App\Transaction', 'imagable_id' => $transaction->id]);
    }


    /**
     * @test
     * users should have see-transaction permission to be allowed
     * users can only see their own transactions
     */
    public function retailer_can_see_a_single_transaction()
    {
        $this->prepNormalEnv('retailer1', ['see-transactions', 'see-costs'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->get($transaction->path())->assertSeeText($transaction->comment);
        // users are not allowed to see other retailers transaction records
        $this->prepNormalEnv('retailer2', ['see-transactions', 'see-costs'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->get($transaction->path())->assertForbidden();
    }

    /**
     * this should be tested in VueJs
     */
    public function form_is_available_to_update_a_transaction()
    {

    }

    /**
     * @test
     * Users are only able to update not confirmed transactions
     */
    public function retailer_can_update_not_confirmed_transactions()
    {
        $this->prepNormalEnv('retailer', ['create-transactions'], 0, 1);
        $retailer1 = Auth::user();
        $this->actingAs($retailer1, 'api');
        // create a transaction
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        // create image for this transaction
        factory('App\Image')->create([
            'user_id' => Auth::user()->id,
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id,
            'image_name' => '/images/transaction1.jpg'
        ]);
        // create image file for transaction record in the image folder
        Storage::disk('public')->put('/images/transaction1.jpg', 'Contents');
        // save old image name
        $oldImageName = $transaction->images()->where('imagable_id', $transaction->id)->value('image_name');
        // creating two types of attributes to test updates with or without images
        $newAttributesWithImage = [
            'currency' => 'USD',
            'amount' => '9999',
            'comment' => 'new comment1',
            'image' => UploadedFile::fake()->create('newPic.jpg')
        ];
        $newAttributesWithoutImage = [
            'currency' => 'USD',
            'amount' => '5555',
            'comment' => 'new comment2',
        ];
        // update record without new image
        $this->patch($transaction->path(), $newAttributesWithoutImage);
        // updated image record should be available on the server
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'currency' => 'USD',
            'amount' => '5555',
            'comment' => $newAttributesWithoutImage['comment'],
        ]);

        // old image file should remain intact
        $this->assertFileExists(public_path('storage' . $oldImageName));
        //update record with new image
        $this->patch($transaction->path(), $newAttributesWithImage);
        // updated transaction record should be available on the server
        $this->assertDatabaseHas('transactions', [
                'id' => $transaction->id,
                'currency' => 'USD',
                'amount' => '9999',
                'comment' => $newAttributesWithImage['comment']]
        );
        // new image file should be uploaded on the server and respective record created in the images table
        $image_name = $transaction->images()->where(['imagable_id' => $transaction->id, 'imagable_type' => 'App\Transaction'])->value('image_name');
        $this->assertFileExists(public_path('storage' . $image_name));
        $this->assertDatabaseHas('images', ['user_id' => Auth::user()->id, 'imagable_type' => 'App\Transaction', 'imagable_id' => $transaction->id]);
        // old image file should be deleted from the server, also respective image record must be updated
        $this->assertFileDoesNotExist(public_path('storage' . $oldImageName));
        $this->assertDatabaseHas('images', ['user_id' => Auth::user()->id, 'image_name' => $image_name, 'imagable_type' => 'App\Transaction' , 'imagable_id' => $transaction->id]);
        //users can only update their own transactions
        $this->prepNormalEnv('retailer2', ['create-transactions'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2, 'api');
        $this->patch($transaction->path(), $newAttributesWithImage)->assertForbidden();
    }

    /** @test
     * retailers can not make any changes to confirmed transactions,so update and delete records are not allowed
     */
    public function retailer_can_not_delete_or_update_confirmed_transactions()
    {
        $this->prepNormalEnv('retailer', ['create-transactions'], 0, 1);
        $retailer = Auth::user();
        $this->actingAs($retailer, 'api');
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id, 'confirmed' => 1]);
        $this->patch($transaction->path())->assertForbidden();
        $this->delete($transaction->path())->assertForbidden();
    }


    /** @test
     * users should have delete-transactions to be allowed
     * transaction record and image file also must be deleted
     */
    public function retailer_can_delete_not_confirmed_transactions()
    {
        $this->prepNormalEnv('retailer1', ['create-transactions', 'delete-transactions'], 0, 1);
        $retailer1 = Auth::user();
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $image = factory('App\Image')->create([
            'user_id' => $retailer1->id,
            'imagable_type' => 'App\Transaction',
            'imagable_id' => $transaction->id,
            'image_name' => '/images/transaction1.jpg'
        ]);
        Storage::disk('public')->put('/images/transaction1.jpg', 'contents');
        $imageName = $image->image_name;
        //users are only able to delete their own transactions
        $this->prepNormalEnv('retailer2', ['create-transactions', 'delete-transactions'], 0, 1);
        $retailer2 = Auth::user();
        $this->actingAs($retailer2);
        $this->delete($transaction->path())->assertForbidden();
        // users can delete their own transactions which has not yet been confirmed
        $this->actingAs($retailer1);
        $this->delete($transaction->path());
        // transaction record must be deleted
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id, 'user_id' => $retailer1->id]);
        // transaction's image record must be deleted
        $this->assertDatabaseMissing('images', ['imagable_id' => $image->id, 'imagable_type' => 'App\Transaction', 'user_id' => $retailer1->id]);
        // transaction's image file must be deleted
        $this->assertFileDoesNotExist(public_path('storage' . $imageName));
    }

    /** @test
     * retailers can not confirm transactions
     * only SystemAdmin is able to confirm transactions
     */
    public function retailers_can_not_confirm_transactions()
    {
        $this->prepNormalEnv('retailer', ['create-transactions'], 0, 1);
        $transaction = factory('App\Transaction')->create(['user_id' => Auth::user()->id]);
        $this->patch('/confirm-transaction/' . $transaction->id)->assertForbidden();
    }

    /** @test */
    public function only_SystemAdmin_can_confirm_transactions()
    {
        $this->withoutExceptionHandling();
        $this->prepNormalEnv('retailer',['create-transactions'], 0 , 1 );
        $retailer = Auth::user();
        $this->prepAdminEnv('SystemAdmin', 0, 1);
        $SystemAdmin = Auth::user();
        $this->actingAs($retailer);
        $transaction = factory('App\Transaction')->create(['user_id' => $retailer->id]);
        $this->actingAs($SystemAdmin);
        $this->patch('/confirm-transaction/' . $transaction->id);
        $this->assertEquals(1, Transaction::where('id', $transaction->id)->value('confirmed'));
    }

    /** @test */
    public function each_transaction_belongs_to_a_user()
    {
        $this->prepNormalEnv('retailer', ['create-transactions'], 0, 1);
        $user = Auth::user();
        $transaction = factory('App\Transaction')->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $transaction->user);
    }

    /** @test */
    public function each_user_can_have_many_transactions()
    {
        $this->prepNormalEnv('retailer', ['create-transactions'], 0, 1);
        $user = Auth::user();
        factory('App\Transaction')->create(['user_id' => $user->id]);
        $this->assertInstanceOf(Transaction::class, $user->transactions->find(1));
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
