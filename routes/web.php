<?php

use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function() {
    Route::get('trips', [TripController::class, 'index'] )->name('trip.index');
    Route::get('trips/create', [TripController::class, 'create'])->name('trip.create');
    Route::post('trips/store', [TripController::class, 'store'])->name('trip.store');
    Route::get('trips/{trip}', [TripController::class, 'show'])->name('trip.show');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
