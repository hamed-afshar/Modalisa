<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');

Route::group(['middleware' => ['auth:api', 'cors']], function () {

    Route::get('/users', 'API\UserController@index')->name('users.index');
    Route::get('/users/{user}', 'API\UserController@show')->name('users.show');
    Route::delete('/users/{user}', 'API\UserController@destroy')->name('users.destroy');
    Route::patch('/lock/{user}', 'API\UserController@lock')->name('users.lock');
    Route::patch('/confirm/{user}', 'API\UserController@confirm')->name('users.confirm');
    Route::patch('/edit-profile/{user}', 'API\UserController@editProfile')->name('users.profile');

    Route::get('/roles', 'API\RoleController@index')->name('roles.index');
    Route::post('/roles', 'API\RoleController@store')->name('roles.store');
    Route::get('/roles/{role}', 'API\RoleController@show')->name('roles.show');
    Route::patch('/roles/{role}', 'API\RoleController@update')->name('roles.update');
    Route::delete('/roles/{role}', 'API\RoleController@destroy')->name('roles.destroy');
    Route::post('/allow-to/{role}/{permission}', 'API\RoleController@allowToPermission')->name('roles.allow');
    Route::post('/disallow-to/{role}/{permission}', 'API\RoleController@disallowToPermission')->name('roles.disallow');
    Route::post('/change-role/{role}/{user}', 'API\RoleController@changeRole')->name('roles.change');

    Route::get('/permissions', 'API\PermissionController@index')->name('permissions.index');
    Route::post('/permissions', 'API\PermissionController@store')->name('permissions.store');
    Route::get('/permissions/{permission}', 'API\PermissionController@show')->name('permissions.show');
    Route::patch('/permissions/{permission}', 'API\PermissionController@update')->name('permissions.update');
    Route::delete('/permissions/{permission}', 'API\PermissionController@destroy')->name('permissions.destroy');

    Route::get('/subscriptions', 'API\SubscriptionController@index')->name('subscriptions.index');
    Route::post('/subscriptions', 'API\SubscriptionController@store')->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}', 'API\SubscriptionController@show')->name('subscriptions.show');
    Route::patch('/subscriptions/{subscription}', 'API\SubscriptionController@update')->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', 'API\SubscriptionController@destroy')->name('subscriptions.destroy');
    Route::post('/change-subscriptions/{subscription}/{user}', 'API\SubscriptionController@changeSubscription')->name('subscriptions.change');

    Route::get('/statuses', 'API\StatusController@index')->name('status.index');
    Route::post('/statuses', 'API\StatusController@store')->name('status.store');
    Route::get('/statuses/{status}', 'API\StatusController@show')->name('status.show');
    Route::patch('/statuses/{status}', 'API\StatusController@update')->name('status.update');
    Route::delete('/statuses/{status}', 'API\StatusController@destroy')->name('status.destroy');



    Route::get('/admin-index-orders', 'API\AdminController@indexOrders')->name('admin.index-order');
    Route::get('/admin-index-orders/{order}', 'API\AdminController@indexSingleOrder')->name('admin.index-single-order');


    Route::get('/kargos', 'API\KargoController@index')->name('kargos.index');


    Route::get('/orders', 'API\OrderController@index')->name('orders.index');
});

