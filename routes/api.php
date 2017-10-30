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
    Route::post('rooms', 'Api\RoomController@store');
    Route::patch('rooms/{room}', 'Api\RoomController@update');

    Route::post('links', 'Api\LinkController@store');
});
