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

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('users', 'UserController@index')
        ->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')
        ->middleware('isAdminOrSelf');
});

Route::prefix('auth')->group(function () {
    /*
     * Публичные роуты.
     * - api/auth/register
     * - api/auth/login
     * - api/auth/refresh
     */
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    /*
     * Роуты только для авторизованных пользователей.
     * - api/auth/user
     * - api/auth/logout
     */
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});
