<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::prefix('/bookings')->group(function (){
//    Route::get('/', [BookingController::class, 'index'])
//        ->name('bookings.index');
//});

Route::get('/bookings', [BookingController::class, 'index'])
    ->name('bookings.index');

Route::post('/bookings', [BookingController::class, 'store'])
    ->name('bookings.store');

Route::get('/opening', [BookingController::class, 'getOpeningHours']);

