<?php

use Illuminate\Http\Request;

Route::get('/airports', 'AirportController@search')
    ->name('airport.search');
Route::post('/flight_check', 'BookingController@flightSearch')
    ->name('flight.search');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
