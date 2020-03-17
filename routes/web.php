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


Route::post('/users', 'UserController@store')->name('users.store');
Route::get('/users/create', 'UserController@create')->name('users.create');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/users', 'UserController@index')->name('users.index')->middleware('AccessProvider:see-users');
    Route::get('/users/{user}', 'UserController@show')->name('users.show')->middleware('AccessProvider:see-users');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit')->middleware('AccessProvider:edit-profile');
    Route::patch('/users/{user}', 'UserController@update')->name('users.update')->middleware('AccessProvider:edit-profile');
    Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy')->middleware('AccessProvider:delete-user');

    Route::get('/roles', 'RoleController@index')->name('roles.index')->middleware('AccessProvider:see-roles');
    Route::get('/roles/create', 'RoleController@create')->name('roles.create')->middleware('AccessProvider:create-roles');
    Route::post('/roles', 'RoleController@store')->name('roles.store')->middleware('AccessProvider:create-roles');
    Route::get('/roles/{role}', 'RoleController@show')->name('roles.show')->middleware('AccessProvider:see-roles');
    Route::get('/roles/{role}/edit', 'RoleController@edit')->name('roles.edit')->middleware('AccessProvider:edit-roles');
    Route::patch('/roles/{role}', 'RoleController@update')->name('roles.update')->middleware('AccessProvider:edit-roles');
    Route::delete('/roles/{role}', 'RoleController@destroy')->name('roles.destroy')->middleware('AccessProvider:delete-roles');

    Route::get('/permissions', 'PermissionController@index')->name('permissions.index')->middleware('AccessProvider:see-permissions');
    Route::get('/permissions/create', 'PermissionController@create')->name('permissions.create')->middleware('AccessProvider:create-permissions');
    Route::post('/permissions', 'PermissionController@store')->name('permissions.store')->middleware('AccessProvider:create-permissions');
    Route::get('/permissions/{permission}', 'PermissionController@show')->name('permissions.show')->middleware('AccessProvider:see-permissions');
    Route::get('/permissions/{permission}/edit', 'PermissionController@edit')->name('permissions.edit')->middleware('AccessProvider:edit-permissions');
    Route::patch('/permissions/{permission}', 'PermissionController@update')->name('permissions.update')->middleware('AccessProvider:edit-permissions');
    Route::delete('/permissions/{permission}', 'PermissionController@destroy')->name('permissions.destroy')->middleware('AccessProvider:delete-permissions');

    Route::get('/subscriptions', 'SubscriptionController@index')->name('subscriptions.index')->middleware('AccessProvider:see-subscriptions');
    Route::get('/subscriptions/create', 'SubscriptionController@create')->name('subscriptions.create')->middleware('AccessProvider:create-subscriptions');
    Route::post('/subscriptions', 'SubscriptionController@store')->name('subscriptions.store')->middleware('AccessProvider:create-subscriptions');
    Route::get('/subscriptions/{subscription}', 'SubscriptionController@show')->name('subscriptions.show')->middleware('AccessProvider:see-subscriptions');
    Route::get('/subscriptions/{subscription}/edit', 'SubscriptionController@edit')->name('subscriptions.edit')->middleware('AccessProvider:edit-subscriptions');
    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update')->name('subscriptions.update')->middleware('AccessProvider:edit-subscriptions');
    Route::delete('/subscriptions/{subscription}', 'SubscriptionController@destroy')->name('subscriptions.destroy')->middleware('AccessProvider:delete-subscriptions');

    Route::get ('/user-subscriptions', 'UserSubscriptionController@index')->name('user-subscriptions.index')->middleware('AccessProvider:edit-subscriptions');
    Route::get('/user-subscriptions/create', 'UserSubscriptionController@create')->name('user-subscription.create')->middleware('AccessProvider:edit-subscriptions');
    Route::post ('/user-subscriptions', 'UserSubscriptionController@store')->name('user-subscriptions.store')->middleware('AccessProvider:edit-subscriptions');
    Route::get ('/user-subscriptions/{userSubscription}', 'UserSubscriptionController@show')->name('user-subscriptions.show')->middleware('AccessProvider:edit-subscriptions');
    Route::get('/user-subscriptions/{userSubscription}/edit', 'UserSubscriptionController@edit')->name('user-subscription.edit')->middleware('AccessProvider:edit-subscriptions');
    Route::patch('/user-subscriptions/{userSubscription}', 'UserSubscriptionController@update')->name('user-subscription.update')->middleware('AccessProvider:edit-subscriptions');
    Route::delete('/user-subscriptions/{userSubscription}', 'UserSubscriptionController@destroy')->name('user-subscription.destroy')->middleware('AccessProvider:edit-subscriptions');

    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/{order}', 'OrdersController@show');
    Route::post('/orders', 'OrdersController@store');








    Route::get('/home', 'HomeController@index')->name('home');
});


Route::delete('/orders', 'OrdersController@destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

