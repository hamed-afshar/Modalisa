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


    Route::get('/users', 'UserController@index')->name('users.index');
    Route::get('/users/{user}', 'UserController@show')->name('users.show');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::patch('/users/{user}', 'UserController@update')->name('users.update');
    Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');
    Route::patch('/edit-profile/{user}', 'UserController@editProfile')->name('users.profile');

    Route::get('/roles', 'RoleController@index')->name('roles.index');
    Route::get('/roles/create', 'RoleController@create')->name('roles.create');
    Route::post('/roles', 'RoleController@store')->name('roles.store');
    Route::get('/roles/{role}', 'RoleController@show')->name('roles.show');
    Route::get('/roles/{role}/edit', 'RoleController@edit')->name('roles.edit');
    Route::patch('/roles/{role}', 'RoleController@update')->name('roles.update');
    Route::delete('/roles/{role}', 'RoleController@destroy')->name('roles.destroy');
    Route::get('/allow-to/{role}/{permission}', 'RoleController@allowToPermission')->name('roles.allow');
    Route::get('/disallow-to/{role}/{permission}', 'RoleController@disallowToPermission')->name('roles.disallow');
    Route::get('/change-role/{role}/{user}', 'RoleController@changeRole')->name('roles.change');

    Route::get('/permissions', 'PermissionController@index')->name('permissions.index');
    Route::get('/permissions/create', 'PermissionController@create')->name('permissions.create');
    Route::post('/permissions', 'PermissionController@store')->name('permissions.store');
    Route::get('/permissions/{permission}', 'PermissionController@show')->name('permissions.show');
    Route::get('/permissions/{permission}/edit', 'PermissionController@edit')->name('permissions.edit');
    Route::patch('/permissions/{permission}', 'PermissionController@update')->name('permissions.update');
    Route::delete('/permissions/{permission}', 'PermissionController@destroy')->name('permissions.destroy');

    Route::get('/subscriptions', 'SubscriptionController@index')->name('subscriptions.index');
    Route::get('/subscriptions/create', 'SubscriptionController@create')->name('subscriptions.create');
    Route::post('/subscriptions', 'SubscriptionController@store')->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}', 'SubscriptionController@show')->name('subscriptions.show');
    Route::get('/subscriptions/{subscription}/edit', 'SubscriptionController@edit')->name('subscriptions.edit');
    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update')->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', 'SubscriptionController@destroy')->name('subscriptions.destroy');
    Route::get('/change-subscriptions/{subscription}/{user}', 'SubscriptionController@changeSubscription')->name('subscriptions.change');

    Route::get('/transactions', 'TransactionController@index')->name('transactions.index');
    Route::get('/transactions/create', 'TransactionController@create')->name('transactions.create');
    Route::post('/transactions', 'TransactionController@store')->name('transactions.store');
    Route::get('/transactions/{transaction}', 'TransactionController@show')->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', 'TransactionController@edit')->name('transactions.edit');
    Route::patch('/transactions/{transaction}', 'TransactionController@update')->name('transactions.update');
    Route::delete('/transactions/{transaction}', 'TransactionController@destroy')->name('transactions.delete');
    Route::patch('/transactions/confirm/{transaction}', 'TransactionController@confirm')->name('transaction.confirm');

    Route::get('/statuses', 'StatusController@index')->name('status.index');
    Route::get('/statuses/create', 'StatusController@create')->name('status.create');
    Route::post('/statuses', 'StatusController@store')->name('status.store');
    Route::get('/statuses/{status}', 'StatusController@show')->name('status.show');
    Route::get('/statuses/{status}/edit', 'StatusController@edit')->name('status.edit');
    Route::patch('/statuses/{status}', 'StatusController@update')->name('status.update');
    Route::delete('/statuses/{status}', 'StatusController@delete')->name('status.delete');

    Route::get('/histories/{product}', 'HistoryController@index')->name('history.index');
    Route::post('/change-history/{product}/{status}', 'HistoryController@store')->name('history.store');
    Route::delete('/histories/{history}', 'HistoryController@destroy')->name('history.delete');

    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/{order}', 'OrdersController@show');
    Route::post('/orders', 'OrdersController@store');


});


Route::delete('/orders', 'OrdersController@destroy');






