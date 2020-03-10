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
    Route::get('/users', 'UserController@index')->name('users.index')->middleware('AccessProvider:see-users, users.index');
    Route::get('/users/{user}', 'UserController@show')->name('users.show')->middleware('AccessProvider:see-users, users.show');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit')->middleware('AccessProvider:edit-profile, users.edit');
    Route::patch('/users/{user}', 'UserController@update')->name('users.update')->middleware('AccessProvider:edit-profile, users.update');
    Route::delete('/users/{user}', 'UserController@destroy')->name('users.delete')->middleware('AccessProvider:delete-user, users.delete');

    Route::get('/roles', 'RoleController@index')->name('roles.index')->middleware('AccessProvider:see-roles, roles.index');
    Route::get('/roles/create', 'RoleController@create')->name('roles.create')->middleware('AccessProvider:create-roles, roles.create');
    Route::post('/roles', 'RoleController@store')->name('roles.store')->middleware('AccessProvider:create-roles, roles.store');
    Route::get('/roles/{role}', 'RoleController@show')->name('roles.show')->middleware('AccessProvider:see-roles, roles.show');
    Route::get('/roles/{role}/edit', 'RoleController@edit')->name('roles.edit')->middleware('AccessProvider:edit-roles, roles.edit');
    Route::patch('/roles/{role}', 'RoleController@update')->name('roles.update')->middleware('AccessProvider:edit-roles, roles.update');
    Route::delete('/roles/{role}', 'RoleController@destroy')->name('roles.delete')->middleware('AccessProvider:delete-roles, roles.delete');

    Route::get('/permissions', 'PermissionController@index')->name('permissions.index')->middleware('AccessProvider:see-permissions, permissions.index');
    Route::get('/permissions/create', 'PermissionController@create')->name('permissions.create')->middleware('AccessProvider:create-permissions, permissions.create');
    Route::post('/permissions', 'PermissionController@store')->name('permissions.store')->middleware('AccessProvider:create-permissions, permissions.store');
    Route::get('/permissions/{permission}', 'PermissionController@show')->name('permissions.show')->middleware('AccessProvider:see-permissions, permissions.show');



    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/{order}', 'OrdersController@show');
    Route::post('/orders', 'OrdersController@store');




    Route::get('/subscriptions', 'SubscriptionController@index');
    Route::post('/subscriptions', 'SubscriptionController@store');
    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update');
    Route::post('/user-subscription', 'SubscriptionController@assignSubscription');
    Route::get('/user-subscription', 'SubscriptionController@indexUserSubscription');



    Route::get('/home', 'HomeController@index')->name('home');
});


Route::delete('/orders', 'OrdersController@destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

