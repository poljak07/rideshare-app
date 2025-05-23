<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');



Route::middleware('auth')->group(function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('trips')->name('trip.')->group(function() {
        Route::get('/', [TripController::class, 'index'] )->name('index');
        Route::get('search', [TripController::class, 'search'])->name('search');
        Route::get('create', [TripController::class, 'create'])->name('create');
        Route::post('store', [TripController::class, 'store'])->name('store');
        Route::put('status/{trip}/{passenger}', [TripController::class, 'updateStatus'])->name('statusUpdate');
        Route::post('request/{trip}', [TripController::class, 'tripRequest'])->name('request');
        Route::delete('cancel/{trip}', [TripController::class, 'tripCancel'])->name('cancel');
        Route::delete('destroy/{trip}', [TripController::class, 'destroy'])->name('destroy');
        Route::get('my-trips', [TripController::class, 'myTrips'])->name('my');
        Route::put('finish/{trip}', [TripController::class, 'tripFinish'])->name('finish');
        Route::put('start/{trip}', [TripController::class, 'tripStart'])->name('start');
        Route::put('location/{trip}', [TripController::class, 'updateLocation'])->name('updateLocation');
        Route::get('{trip}', [TripController::class, 'show'])->name('show');

    });

    Route::prefix('cars')->name('car.')->group(function() {
        Route::get('create', [CarController::class, 'create'])->name('create');
        Route::post('store', [CarController::class, 'store'])->name('store');
        Route::get('{car}', [CarController::class, 'show'])->name('show');
    });

    Route::get('/reverse-geocode', LocationController::class);
});

Route::get('/faq', function () {
    return Inertia::render('Faq');
})->name('faq');

Route::get('/how-does-this-work', function () {
    return Inertia::render('WorkPrinciple');
})->name('how-does-this-work');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


