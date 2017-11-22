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


/*
|--------------------------------------------------------------------------
| Unprotected API Routes
|--------------------------------------------------------------------------
|
*/

// Link Access


/*
|--------------------------------------------------------------------------
| Protected API Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:api')->group(function () {

    // Room endpoints
    Route::post('rooms', 'Api\RoomController@store');
    Route::get('rooms', 'Api\RoomController@index');
    Route::post('rooms', 'Api\RoomController@store');
    Route::patch('rooms/{room}', 'Api\RoomController@update');

    // Link endpoints
    Route::get('links/all', 'Api\LinkController@index');
    Route::get('links/all/{id}', 'Api\LinkController@show');
    Route::patch('links/all/{id}', 'Api\LinkController@update');

    // User Link Access
    Route::get('links', 'Api\UserLinkController@index');
    Route::get('links/{id}', 'Api\UserLinkController@show');
    Route::post('links', 'Api\UserLinkController@store');


});
