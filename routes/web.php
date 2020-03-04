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


Route::group(['middleware' => 'auth'], function() {
    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/{order}', 'OrdersController@show');
    Route::post('/orders', 'OrdersController@store');

    Route::get('/users', 'UserController@getAllUserList');
    Route::get('/user/{user}', 'UserController@showUserProfile');
    Route::patch('/users/{user}', 'UserController@update');

    Route::get('/subscriptions', 'SubscriptionController@index');
    Route::post('/subscriptions', 'SubscriptionController@store');
    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update');
    Route::post('/user-subscription', 'SubscriptionController@assignSubscription');
    Route::get('/user-subscription', 'SubscriptionController@indexUserSubscription');

    Route::get('/roles', 'RoleController@index');
    Route::post('/roles', 'RoleController@store');
    Route::get('/roles/create', 'RoleController@create');
    Route::get('/roles/{id}', 'RoleController@show');
    Route::get('/roles/{id}/edit', 'RoleController@edit');
    Route::patch('/roles/{role}', 'RoleController@update');


    Route::get('/home', 'HomeController@index')->name('home');
});

Route::post('/users', 'UserController@register');
Route::delete('/users', 'UserController@destroy');
Route::get('/create', 'UserController@create');
Route::delete('/orders', 'OrdersController@destroy');
Route::get('/access-denied', 'UserController@showAccessDenied');
Route::get('/pending-for-confirmation', 'UserController@showPendingForConfirmation');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
