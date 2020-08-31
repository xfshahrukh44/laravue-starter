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

// ADMIN PANEL ROUTES---------------------------------------
Route::group(['middleware' => 'web'], function() {  
    Route::apiResources(['user'=>'API\UserController']);
});
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------




// API ROUTES---------------------------------------

// open routes
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'API\AuthController@login');
    Route::post('register', 'API\AuthController@store');
    Route::get('get_banner', 'BannerController@get_banner')->name('get_banner');
});

// closed routes
Route::group([
    'middleware' => 'auth:api',
    // 'namespace' => 'App\Http\Controllers\API',
    'prefix' => 'auth'

], function ($router) {
    // User
    Route::post('logout', 'API\AuthController@logout');
    Route::post('me', 'API\AuthController@me');
    Route::apiResources(['user'=>'API\AuthController']);
    // BASKET
    Route::apiResources(['basket'=>'API\BasketController']);
    // ITEM
    Route::apiResources(['item'=>'API\ItemController']);
    // UNIT
    Route::apiResources(['unit'=>'API\UnitController']);
    // BasketItem
    Route::apiResources(['basket_item'=>'API\BasketItemController']);
    // Order
    Route::apiResources(['order'=>'API\OrderController']);
    Route::post('convert_into_basket', 'API\OrderController@convert_into_basket');
    // OrderItem
    Route::apiResources(['order_item'=>'API\OrderItemController']);

});

