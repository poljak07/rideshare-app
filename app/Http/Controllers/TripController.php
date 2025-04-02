<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\json;


class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = Trip::where('is_started', 0)
            ->paginate(5)
            ->through(function ($trip) {
                return [
                    'id' => $trip->id,
                    'destination' => json_decode($trip->destination, true),
                    'driverLocation' => json_decode($trip->driver_location, true),
                    'destination_name' => $trip->destination_name,
                    'origin' => $trip->origin,
                    'driver_name' => $trip->user->name,
                    'created_at' => $trip->created_at->diffForHumans(),
                    'updated_at' => $trip->updated_at->diffForHumans(),
                ];
        });

        return Inertia::render('trip/Index', [
            'trips' => $trips,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('trip/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'location' => ['required'],
            'place' => ['required'],
            'startingplace' => ['required'],
        ]);

        $trip = Trip::create([
            'user_id' => Auth::id(),
            'car_id' => Auth::user()->car->id,
            'destination' => json_encode($request->input('location')),
            'destination_name' => $request->input('place'),
            'origin' => $request->input('startingplace'),
            'driver_location' => json_encode($request->input('startingLocation')),
        ]);

        return redirect(route('trip.show', $trip->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {

        return Inertia::render('trip/Show', [
            'trip' => [
                'id' => $trip->id,
                'destination' => json_decode($trip->destination, true),
                'driverLocation' => json_decode($trip->driver_location, true),
                'destination_name' => $trip->destination_name,
                'origin' => $trip->origin,
                'driver_name' => $trip->user->name,
                'created_at' => $trip->created_at->diffForHumans(),
                'updated_at' => $trip->updated_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        //
    }

}
