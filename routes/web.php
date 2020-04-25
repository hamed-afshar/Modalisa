<?php

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

route::get('/others/pending-for-confirmation', 'RegisterController@pending')->name('pending');

Route::post('/users', 'RegisterController@store')->name('users.store');
Route::get('/users/create', 'RegisterController@create')->name('users.create');

Route::group(['middleware' => 'auth'], function() {

    Route::get('/users', 'UserController@index')->name('users.index');
    Route::get('/users/{user}', 'UserController@show')->name('users.show');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::patch('/users/{user}', 'UserController@update')->name('users.update');
    Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');

    Route::get('/roles', 'RoleController@index')->name('roles.index');
    Route::get('/roles/create', 'RoleController@create')->name('roles.create');
    Route::post('/roles', 'RoleController@store')->name('roles.store');
    Route::get('/roles/{role}', 'RoleController@show')->name('roles.show');
    Route::get('/roles/{role}/edit', 'RoleController@edit')->name('roles.edit');
    Route::patch('/roles/{role}', 'RoleController@update')->name('roles.update');
    Route::delete('/roles/{role}', 'RoleController@destroy')->name('roles.destroy');

    Route::get('/permissions', 'PermissionController@index')->name('permissions.index');
    Route::get('/permissions/create', 'PermissionController@create')->name('permissions.create');
    Route::post('/permissions', 'PermissionController@store')->name('permissions.store');
    Route::get('/permissions/{permission}', 'PermissionController@show')->name('permissions.show');
    Route::get('/permissions/{permission}/edit', 'PermissionController@edit')->name('permissions.edit');
    Route::patch('/permissions/{permission}', 'PermissionController@update')->name('permissions.update');
    Route::delete('/permissions/{permission}', 'PermissionController@destroy')->name('permissions.destroy');

    Route::get('/subscriptions', 'SubscriptionController@index')->name('subscriptions.index')->middleware('AccessProvider:see-subscriptions');
    Route::get('/subscriptions/create', 'SubscriptionController@create')->name('subscriptions.create')->middleware('AccessProvider:create-subscriptions');
    Route::post('/subscriptions', 'SubscriptionController@store')->name('subscriptions.store')->middleware('AccessProvider:create-subscriptions');
    Route::get('/subscriptions/{subscription}', 'SubscriptionController@show')->name('subscriptions.show')->middleware('AccessProvider:see-subscriptions');
    Route::get('/subscriptions/{subscription}/edit', 'SubscriptionController@edit')->name('subscriptions.edit')->middleware('AccessProvider:edit-subscriptions');
    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update')->name('subscriptions.update')->middleware('AccessProvider:edit-subscriptions');
    Route::delete('/subscriptions/{subscription}', 'SubscriptionController@destroy')->name('subscriptions.destroy')->middleware('AccessProvider:delete-subscriptions');

    Route::get ('/user/subscriptions', 'UserSubscriptionController@index')->name('user-subscriptions.index')->middleware('AccessProvider:edit-subscriptions');
    Route::get('/user/subscriptions/create', 'UserSubscriptionController@create')->name('user-subscription.create')->middleware('AccessProvider:edit-subscriptions');
    Route::post ('/user/subscriptions', 'UserSubscriptionController@store')->name('user-subscriptions.store')->middleware('AccessProvider:edit-subscriptions');
    Route::get ('/user/subscriptions/{userSubscription}', 'UserSubscriptionController@show')->name('user-subscriptions.show')->middleware('AccessProvider:edit-subscriptions');
    Route::get('/user/subscriptions/{userSubscription}/edit', 'UserSubscriptionController@edit')->name('user-subscription.edit')->middleware('AccessProvider:edit-subscriptions');
    Route::patch('/user/subscriptions/{userSubscription}', 'UserSubscriptionController@update')->name('user-subscription.update')->middleware('AccessProvider:edit-subscriptions');
    Route::delete('/user/subscriptions/{userSubscription}', 'UserSubscriptionController@destroy')->name('user-subscription.destroy')->middleware('AccessProvider:edit-subscriptions');


    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/{order}', 'OrdersController@show');
    Route::post('/orders', 'OrdersController@store');

    Route::get('/home', 'HomeController@index')->name('home');
});


Route::delete('/orders', 'OrdersController@destroy');

Auth::routes();

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/retailer', function () {
    return view('dashboards.retailer');
});

Route::get('/system-admin', function () {
    return view('dashboards.system-admin');
});

