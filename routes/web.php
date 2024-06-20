<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AvailabilityController;

// Home route
Route::get('/', [PageController::class, 'container'])->name('items.index');

// Booking routes
Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('bookings/store', [BookingController::class, 'store'])->name('bookings.store');

// Availability API route
Route::get('api/availability/{itemId}', [AvailabilityController::class, 'show']);

