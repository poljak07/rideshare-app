<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $trips = Trip::where('user_id', $userId)->get();
        $cars = Car::where('user_id', $userId)->get();

        return Inertia::render('Dashboard',[
            'trips' => $trips,
            'cars' => $cars,
        ]);
    }
}
