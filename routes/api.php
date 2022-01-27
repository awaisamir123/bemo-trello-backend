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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {

    Route::group(['prefix' => 'list'], function() {
        Route::get('', 'App\Http\Controllers\ColumnController@index')->middleware('isValid');
        Route::post('', 'App\Http\Controllers\ColumnController@create');
        Route::post('column/arrangement', 'App\Http\Controllers\ColumnController@columnArrangement');
        Route::put('/{id}', 'App\Http\Controllers\ColumnController@update');
        Route::delete('{id}', 'App\Http\Controllers\ColumnController@destroy');
    });

    Route::group(['prefix' => 'card'], function() {
        Route::get('', 'App\Http\Controllers\CardController@index');
        Route::get('detail/{id}', 'App\Http\Controllers\CardController@view');
        Route::post('', 'App\Http\Controllers\CardController@create');
        Route::put('{id}', 'App\Http\Controllers\CardController@update');
        Route::delete('{id}', 'App\Http\Controllers\CardController@destroy');
    });

    Route::get('db-export', 'App\Http\Controllers\DbExportController@export');



});
