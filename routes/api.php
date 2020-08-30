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

// ADMIN PANEL ROUTES
Route::group(['middleware' => 'auth:admin_api'], function() {  
    Route::apiResources(['user'=>'API\UserController']);
});


// API ROUTES
// open routes
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'API\AuthController@login');
    Route::post('register', 'API\AuthController@store');
});

// closed routes
Route::group([
    'middleware' => 'auth:api',
    // 'namespace' => 'App\Http\Controllers\API',
    'prefix' => 'auth'

], function ($router) {
    Route::post('logout', 'API\AuthController@logout');
    Route::post('refresh', 'API\AuthController@refresh');
    Route::post('me', 'API\AuthController@me');
    Route::apiResources(['user'=>'API\AuthController']);
});


