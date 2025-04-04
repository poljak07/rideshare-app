<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::middleware('auth')->group(function() {
    Route::get('trips', [TripController::class, 'index'] )->name('trip.index');
    Route::get('trips/search', [TripController::class, 'search'])->name('trip.search');
    Route::get('trips/create', [TripController::class, 'create'])->name('trip.create');
    Route::post('trips/store', [TripController::class, 'store'])->name('trip.store');
    Route::get('trips/{trip}', [TripController::class, 'show'])->name('trip.show');
    Route::get('cars/create', [CarController::class, 'create'])->name('car.create');
    Route::post('cars/store', [CarController::class, 'store'])->name('car.store');
    Route::get('cars/{car}', [CarController::class, 'show'])->name('car.show');



});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
