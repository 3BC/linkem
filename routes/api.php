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
Route::post('links', 'Api\LinkController@store');

Route::middleware('auth:api')->group(function () {
    Route::post('rooms', 'Api\RoomController@store');
    Route::patch('rooms/{room}', 'Api\RoomController@update');
});
