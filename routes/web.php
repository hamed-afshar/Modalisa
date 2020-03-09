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


Route::post('/users', 'UserController@store')->name('user-register');
Route::get('/users/create', 'UserController@create')->name('user-register-form');
Route::get('/pending-for-confirmation', 'UserController@showPendingForConfirmation')->name('pending-for-confirmation');
Route::get('/locked', 'UserController@showLocked')->name('show-locked');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/users', 'UserController@index')->name('all-users')->middleware('AccessProvider:see-users, all-users');
    Route::get('/users/{user}', 'UserController@show')->name('show-user')->middleware('AccessProvider:see-users, show-user');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('user-edit-form')->middleware('AccessProvider:edit-profile, user-edit-form');
    Route::patch('/users/{user}', 'UserController@update')->name('user-profile')->middleware('AccessProvider:edit-profile, user-profile');
    Route::delete('/users/{user}', 'UserController@destroy')->name('user-delete')->middleware('AccessProvider:delete-user, user-delete');

    Route::get('/roles', 'RoleController@index')->name('all-roles')->middleware('AccessProvider:see-roles, all-roles');
    Route::get('/roles/create', 'RoleController@create')->name('role-create-form')->middleware('AccessProvider:create-roles, role-create-form');
    Route::post('/roles', 'RoleController@store')->name('save-role')->middleware('AccessProvider:create-roles, save-role');
    Route::get('/roles/{role}', 'RoleController@show')->name('show-role')->middleware('AccessProvider:see-roles, show-role');
    Route::get('/roles/{role}/edit', 'RoleController@edit')->name('role-edit-form')->middleware('AccessProvider:edit-roles, role-edit-form');
    Route::patch('/roles/{role}', 'RoleController@update')->name('update-role')->middleware('AccessProvider:edit-roles, update-role');
    Route::delete('/roles/{role}', 'RoleController@destroy')->name('delete-role')->middleware('AccessProvider:delete-roles, delete-role');

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

