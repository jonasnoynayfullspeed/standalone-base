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


Route::prefix('home')->group(function () {
    Route::middleware('auth.token')->group(function() {
        //
    });

    Route::post('getOnAirLive', 'LiveController@getOnAirLive');
    Route::post('createLive', 'LiveController@createLive');
    Route::post('stopLive', 'LiveController@stopLive');
    Route::post('useTicket', 'TicketController@useTicket');
});