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


Route::post('/users', 'UserController@store');
Route::get('/users/create', 'UserController@create');
Route::delete('/users', 'UserController@destroy');
Route::get('/pending-for-confirmation', 'UserController@showPendingForConfirmation');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/{order}', 'OrdersController@show');
    Route::post('/orders', 'OrdersController@store');

    Route::get('/users', 'UserController@index');
    Route::get('/users/{user}', 'UserController@show');
    Route::get('/users/{user}/edit', 'UserController@edit');
    Route::patch('/users/{user}', 'UserController@update');
    Route::delete('/users/{user}', 'UserController@destroy');


    Route::get('/subscriptions', 'SubscriptionController@index');
    Route::post('/subscriptions', 'SubscriptionController@store');
    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update');
    Route::post('/user-subscription', 'SubscriptionController@assignSubscription');
    Route::get('/user-subscription', 'SubscriptionController@indexUserSubscription');

    Route::get('/roles', 'RoleController@index');
    Route::post('/roles', 'RoleController@store');
    Route::get('/roles/create', 'RoleController@create');
    Route::get('/roles/{role}', 'RoleController@show');
    Route::get('/roles/{role}/edit', 'RoleController@edit');
    Route::patch('/roles/{role}', 'RoleController@update');
    Route::delete('/roles/{role}', 'RoleController@destroy');


    Route::get('/home', 'HomeController@index')->name('home');
});


Route::delete('/orders', 'OrdersController@destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

