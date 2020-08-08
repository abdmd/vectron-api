<?php

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'UsersController@login');
    Route::post('register', 'UsersController@register');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'UsersController@logout');        
        Route::get('users', 'UsersController@getUsers');
        Route::get('users/{id}', 'UsersController@getUserById');
        Route::put('users/{id}', 'UsersController@updateUser')->where('id', '[0-9]+');
        Route::delete('users/{id}', 'UsersController@deleteUser')->middleware('role:admin');
        
        // authenticated user
        Route::get('user', 'UsersController@getCurrentuser');
        Route::put('user/password', 'UsersController@changePassword');
    });
});