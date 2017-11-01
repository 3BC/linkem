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

Route::get('links', 'Api\LinkController@index');

// Routes Protected by Auth
Route::middleware('auth:api')->group(function () {
    Route::get('rooms', 'Api\RoomController@index');
    Route::post('rooms', 'Api\RoomController@store');
    Route::patch('rooms/{room}', 'Api\RoomController@update');

    Route::post('links', 'Api\LinkController@store');
    Route::get('links/all', 'Api\LinkController@index');
    Route::get('links/all/{id}', 'Api\LinkController@show');

    // User Link Access
    Route::get('links', 'Api\UserLinkController@index');
<<<<<<< HEAD
    Route::get('links/{id}', 'Api\UserLinkController@show');
=======
>>>>>>> Get all links tests completed and passing.

});
