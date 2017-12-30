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

    // Group endpoints
    Route::get('groups', 'Api\GroupController@index');
    Route::get('groups/list', 'Api\GroupController@fullIndex');

    Route::post('groups', 'Api\GroupController@store');

    Route::patch('groups/{group}', 'Api\GroupController@update');
    Route::delete('groups/{group}', 'Api\GroupController@destroy');

    // Link endpoints
    Route::get('links', 'Api\LinkController@index');
    Route::get('links/list', 'Api\LinkController@fullIndex');

    Route::post('links', 'Api\Linkcontroller@store');

    Route::get('links/all/{id}', 'Api\LinkController@show');
    Route::patch('links/all/{id}', 'Api\LinkController@update');


});
