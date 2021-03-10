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

Route::get('/users', 'API\UserController@index');
//    Route::get('/users', 'API\UserController@index')->name('users.index');
//    Route::get('/users/{user}', 'API\UserController@show')->name('users.show');
//    Route::get('/users/{user}/edit', 'API\UserController@edit')->name('users.edit');
//    Route::patch('/users/{user}', 'API\UserController@update')->name('users.update');
//    Route::delete('/users/{user}', 'API\UserController@destroy')->name('users.destroy');
//    Route::patch('/edit-profile/{user}', 'API\UserController@editProfile')->name('users.profile');
