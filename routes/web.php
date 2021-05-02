<?php

use Illuminate\Support\Facades\Route;
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes(['verify' => true]);

Route::get('/others/pending-for-confirmation', 'RegisterController@pending')->name('pending');

Route::group(['middleware' => 'auth'], function() {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/security-center', 'HomeController@security_center')->name('security-center');
    Route::get('/user-center', 'HomeController@user_center')->name('user-center');


//    Route::get('/users', 'UserController@index')->name('users.index');
//    Route::get('/users/{user}', 'UserController@show')->name('users.show');
//    Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::patch('/users/{user}', 'UserController@update')->name('users.update');
//    Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');
//    Route::patch('/edit-profile/{user}', 'UserController@editProfile')->name('users.profile');

//    Route::get('/roles', 'RoleController@index')->name('roles.index');
//    Route::get('/roles/create', 'RoleController@create')->name('roles.create');
//    Route::post('/roles', 'RoleController@store')->name('roles.store');
//    Route::get('/roles/{role}', 'RoleController@show')->name('roles.show');
//    Route::get('/roles/{role}/edit', 'RoleController@edit')->name('roles.edit');
//    Route::patch('/roles/{role}', 'RoleController@update')->name('roles.update');
//    Route::delete('/roles/{role}', 'RoleController@destroy')->name('roles.destroy');
//    Route::get('/allow-to/{role}/{permission}', 'RoleController@allowToPermission')->name('roles.allow');
//    Route::get('/disallow-to/{role}/{permission}', 'RoleController@disallowToPermission')->name('roles.disallow');
//    Route::get('/change-role/{role}/{user}', 'RoleController@changeRole')->name('roles.change');

//    Route::get('/permissions', 'PermissionController@index')->name('permissions.index');
//    Route::get('/permissions/create', 'PermissionController@create')->name('permissions.create');
//    Route::post('/permissions', 'PermissionController@store')->name('permissions.store');
//    Route::get('/permissions/{permission}', 'PermissionController@show')->name('permissions.show');
//    Route::get('/permissions/{permission}/edit', 'PermissionController@edit')->name('permissions.edit');
//    Route::patch('/permissions/{permission}', 'PermissionController@update')->name('permissions.update');
//    Route::delete('/permissions/{permission}', 'PermissionController@destroy')->name('permissions.destroy');

//    Route::get('/subscriptions', 'SubscriptionController@index')->name('subscriptions.index');
//    Route::get('/subscriptions/create', 'SubscriptionController@create')->name('subscriptions.create');
//    Route::post('/subscriptions', 'SubscriptionController@store')->name('subscriptions.store');
//    Route::get('/subscriptions/{subscription}', 'SubscriptionController@show')->name('subscriptions.show');
//    Route::get('/subscriptions/{subscription}/edit', 'SubscriptionController@edit')->name('subscriptions.edit');
//    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update')->name('subscriptions.update');
//    Route::delete('/subscriptions/{subscription}', 'SubscriptionController@destroy')->name('subscriptions.destroy');
//    Route::get('/change-subscriptions/{subscription}/{user}', 'SubscriptionController@changeSubscription')->name('subscriptions.change');

    Route::get('/transactions', 'TransactionController@index')->name('transactions.index');
    Route::get('/transactions/create', 'TransactionController@create')->name('transactions.create');
    Route::post('/transactions', 'TransactionController@store')->name('transactions.store');
    Route::get('/transactions/{transaction}', 'TransactionController@show')->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', 'TransactionController@edit')->name('transactions.edit');
    Route::patch('/transactions/{transaction}', 'TransactionController@update')->name('transactions.update');
    Route::delete('/transactions/{transaction}', 'TransactionController@destroy')->name('transactions.destroy');

//    Route::get('/statuses', 'StatusController@index')->name('status.index');
//    Route::get('/statuses/create', 'StatusController@create')->name('status.create');
//    Route::post('/statuses', 'StatusController@store')->name('status.store');
//    Route::get('/statuses/{status}', 'StatusController@show')->name('status.show');
//    Route::get('/statuses/{status}/edit', 'StatusController@edit')->name('status.edit');
//    Route::patch('/statuses/{status}', 'StatusController@update')->name('status.update');
//    Route::delete('/statuses/{status}', 'StatusController@destroy')->name('status.destroy');

    Route::get('/histories/{product}', 'HistoryController@index')->name('history.index');
    Route::post('/histories', 'HistoryController@store')->name('history.store');
    Route::delete('/histories/{history}', 'HistoryController@destroy')->name('history.destroy');

    Route::get('/orders', 'OrderController@index')->name('orders.index');
    Route::get('/orders/create', 'OrderController@create')->name('orders.create');
    Route::post('/orders', 'OrderController@store')->name('orders.store');

    Route::post('/add-to-order/{order}', 'OrderController@addToOrder')->name('orders.addTo');
    Route::delete('/delete-product/{product}', 'OrderController@deleteProduct')->name('orders.deleteProduct');
    Route::post('/assign-customer/{customer}/{order}', 'OrderController@assignCustomer')->name('orders.assignCustomer');
    Route::patch('/edit-product/{product}', 'OrderController@editProduct')->name('orders.editProduct');

    Route::get('/customers', 'CustomerController@index')->name('customers.index');
    Route::get('/customers/create', 'CustomerController@create')->name('customers.create');
    Route::post('/customers', 'CustomerController@store')->name('customers.store');
    Route::get('/customers/{customer}', 'CustomerController@show')->name('customers.show');
    Route::get('/customers/{customer}/edit', 'CustomerController@edit')->name('customers.edit');
    Route::patch('/customers/{customer}', 'CustomerController@update')->name('customers.update');
    Route::delete('/customers/{customer}', 'CustomerController@destroy')->name('customers.destroy');

    Route::get('/notes/{id}/{model}' , 'NoteController@index')->name('notes.index');
    Route::get('/notes/create', 'NoteController@create')->name('notes.create');
    Route::post('/notes', 'NoteController@store')->name('note.store');
    Route::get('/notes/{note}' , 'NoteController@show')->name('notes.show');
    Route::get('/notes/{note}/edit' , 'NoteController@edit')->name('note.edit');
    Route::patch('/notes/{note}', 'NoteController@update')->name('notes.update');
    Route::delete('/notes/{note}', 'NoteController@destroy')->name('notes.destroy');

    Route::get('/images' , 'ImageController@index')->name('images.index');
    Route::get('/images/create', 'Imagecontroller@create')->name('images.create');
    Route::post('/images' , 'ImageController@store')->name('images.store');
    Route::get('/images/{image}', 'ImageController@show')->name('images.show');
    Route::get('/images/{image}/edit', 'ImageController@edit')->name('images.edit');
    Route::patch('/images/{image}', 'ImageController@update')->name('images.update');
    Route::delete('/images/{image}', 'ImageController@destroy')->name('images.destroy');

    Route::get('/costs', 'CostController@index')->name('costs.index');
    Route::get('/costs/create', 'CostController@create')->name('costs.create');
    Route::post('/costs', 'CostController@store')->name('costs.store');
    Route::get('/costs/{cost}', 'CostController@show')->name('costs.show');
    Route::get('/costs/{cost}/edit', 'CostController@edit')->name('costs.edit');
    Route::patch('/costs/{cost}', 'CostController@update')->name('costs.update');
    Route::delete('/costs/{cost}', 'CostController@destroy')->name('costs.destroy');
    Route::get('/costs-model/{id}/{model}', 'CostController@indexModel')->name('cost.indexModel');

//    Route::get('/kargos', 'KargoController@index')->name('kargos.index');
//    Route::get('/kargos/create', 'KargoController@create')->name('kargos.create');
//    Route::post('/kargos', 'KargoController@store')->name('kargos.store');
//    Route::get('/kargos/{kargo}', 'KargoController@show')->name('kargos.show');
//    Route::get('/kargos/{kargo}/edit', 'KargoController@edit')->name('edit');
//    Route::patch('/kargos/{kargo}', 'KargoController@update')->name('kargos.update');
//    Route::delete('/kargos/{kargo}', 'KargoController@destroy')->name('kargos.destroy');
//    Route::patch('/add-to-kargo/{kargo}/{product}', 'KargoController@addTO')->name('kargos.add');
//    Route::patch('/remove-from-kargo/{kargo}/{product}', 'KargoController@removeFrom')->name('kargos.remove');


    Route::get('/admin-index-costs/{user}', 'AdminController@indexCosts')->name('admin.index-costs');
    Route::get('/admin-index-single-cost/{user}/{cost}', 'AdminController@showCost' )->name('admin.show-cost');
    Route::post('/admin-create-cost/', 'AdminController@storeCost')->name('admin.create-cost');
    Route::patch('/admin-update-cost/{cost}', 'AdminController@updateCost')->name('admin.update-cost');
    Route::delete('/admin-delete-cost/{cost}', 'AdminController@deleteCost')->name('admin.delete-cost');
//    Route::get('/admin-index-kargos', 'AdminController@indexKargos')->name('admin.index-kargos');
//    Route::get('/admin-index-single-kargo/{kargo}', 'AdminController@showKargo')->name('admin.show-kargo');
//    Route::post('/admin-create-kargo/{user}', 'AdminController@storeKargo')->name('admin.create-kargo');
//    Route::patch('/confirm-kargo/{kargo}', 'AdminController@confirmKargo')->name('admin.confirm-kargo');
//    Route::patch('/update-kargo/{user}/{kargo}', 'AdminController@updateKargo')->name('admin.update-kargo');
//    Route::delete('/delete-kargo/{user}/{kargo}', 'AdminController@deleteKargo')->name('admin.delete-kargo');
//    Route::patch('/admin-add-to-kargo/{user}/{kargo}/{product}', 'AdminController@addTOKargo')->name('admin.add-to-kargo');
//    Route::patch('/admin-remove-from-kargo/{kargo}/{product}', 'AdminController@removeFromKargo')->name('admin.remove-from-kargo');
    Route::patch('/confirm-transaction/{transaction}', 'AdminController@confirmTransaction')->name('admin.confirm-transaction');
//    Route::get('/admin-index-orders', 'AdminController@indexOrders')->name('admin.index-order');
//    Route::get('/admin-index-orders/{order}', 'AdminController@indexSingleOrder')->name('admin.index-single-order');

});








