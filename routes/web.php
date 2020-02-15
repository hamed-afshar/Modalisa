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
    Route::get('/users', 'RegisterController@getAllUserList');
    Route::get('/user/{user}', 'RegisterController@showUserProfile');
    Route::patch('/users/{user}', 'RegisterController@update');
    Route::post('/subscriptions', 'SubscriptionController@store');
    Route::patch('/subscriptions/{subscription}', 'SubscriptionController@update');
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::post('/users', 'RegisterController@register');
Route::get('/access-denied', 'RegisterController@showAccessDenied');
Route::get('/pending-for-confirmation', 'RegisterController@showPendingForConfirmation');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
