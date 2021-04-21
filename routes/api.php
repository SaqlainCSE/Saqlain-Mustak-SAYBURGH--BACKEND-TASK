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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/auth/createToken', 'API\Auth\LoginAPIController@createToken');

Route::group([
    'prefix' => 'auth',
], function () {
    Route::get('createToken', 'API\Auth\LoginAPIController@createToken');
    Route::get('refreshToken', 'API\Auth\LoginAPIController@refreshToken'); 
    Route::post('login', 'API\Auth\LoginAPIController@login');
    Route::post('register', 'API\Auth\RegisterAPIController@register');
    Route::post('reset_request', 'API\Auth\PasswordResetController@create');
    Route::get('find/{token}', 'API\Auth\PasswordResetController@find');
    Route::post('reset', 'API\Auth\PasswordResetController@reset');
});

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('logout', 'API\Auth\AuthController@logout');
    Route::get('user', 'API\Auth\AuthController@auth_user');
});

Route::middleware('auth:api', 'throttle:60,1')->group(function () {

    Route::get('/roles', 'API\RoleController@index');
    Route::post('/role/change', 'API\RoleController@change_role');
});

Route::get('/auth/users', 'API\UserController@index');
Route::get('/auth/users/{id}', 'API\UserController@show');
Route::apiResource("users", "API\UserController");
