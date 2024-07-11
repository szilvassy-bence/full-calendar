<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

//Route::get('/bookings', [BookingController::class, 'index'])
//    ->name('bookings.index');
//
//Route::post('/bookings', [BookingController::class, 'store'])
//    ->name('bookings.store');



require __DIR__.'/auth.php';
