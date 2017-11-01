<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->group(function () {
    Route::post('rooms', 'Api\RoomController@store');

    // Full Link Access
    Route::post('links', 'Api\LinkController@store');
    Route::get('links/all', 'Api\LinkController@index');
    Route::get('links/all/{id}', 'Api\LinkController@show');

    // User Link Access
    Route::get('links', 'Api\UserLinkController@index');
    Route::get('links/{id}', 'Api\UserLinkController@show');

});
