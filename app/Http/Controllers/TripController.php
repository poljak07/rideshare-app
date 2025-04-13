<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Trip;
use App\Models\TripPassenger;
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
        $trips = Trip::with(['user', 'passengers' => function ($query) {
            $query->where('user_id', auth()->id())->select('trip_id', 'status');
        }])
            ->where('is_started', 0)
            ->paginate(5)
            ->through(fn ($trip) => $this->formatTrip($trip));

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
            'seats' => ['required', 'numeric'],
            ]);

        $trip = Trip::create([
            'user_id' => Auth::id(),
            'car_id' => Auth::user()->car->id,
            'destination' => json_encode($request->input('location')),
            'destination_name' => $request->input('place'),
            'price' => $request->input('price'),
            'origin' => $request->input('startingplace'),
            'seats' => $request->input('seats'),
            'driver_location' => json_encode($request->input('startingLocation')),
        ]);

        return redirect(route('trip.show', $trip->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $userRequest = $trip->passengers()->where('user_id', auth()->id())->first();

        return Inertia::render('trip/Show', [
            'trip' => $this->formatTrip($trip),
            'userRequestStatus' => $userRequest?->status
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

    public function search(Request $request)
    {
        $trips = Trip::with('user')
        ->when($request->startingplace, fn ($q) => $q->where('origin', 'LIKE', "%{$request->startingplace}%"))
            ->when($request->place, fn ($q) => $q->where('destination_name', 'LIKE', "%{$request->place}%"))
            ->paginate(5)
            ->through(fn ($trip) => $this->formatTrip($trip));


        return Inertia::render('trip/Index', [
            'trips' => $trips->appends($request->query())
        ]);
    }

    private function formatTrip(Trip $trip)
    {
        return [
            'id' => $trip->id,
            'destination' => json_decode($trip->destination, true),
            'driverLocation' => json_decode($trip->driver_location, true),
            'destination_name' => $trip->destination_name,
            'origin' => $trip->origin,
            'driver_name' => $trip->user->name,
            'price' => $trip->price,
            'requestStatus' => $trip->passengers->first()->status ?? null,
            'created_at' => $trip->created_at->diffForHumans(),
            'updated_at' => $trip->updated_at->diffForHumans(),
        ];
    }

    public function tripRequest(Request $request, Trip $trip)
    {
        // Check if user already requested
        if ($trip->passengers()->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['message' => 'Already requested to join this trip.']);
        }

        // Check car capacity
        $passengerCount = $trip->passengers()->count();
        $carCapacity = $trip->seats;

        if ($passengerCount >= $carCapacity) {
            return back()->withErrors(['message' => 'Trip is already full.']);
        }

        // Create the passenger request
        TripPassenger::create([
            'user_id' => Auth::id(),
            'trip_id' => $trip->id,
            'status' => "Pending",
        ]);

        //return back()->with('success', 'Ride request sent successfully!');
    }

    public function tripCancel(Request $request, Trip $trip)
    {
        $tripPassenger = TripPassenger::where('trip_id', $trip->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$tripPassenger) {
            return back()->withErrors(['error' => 'Request not found.']);
        }

        if ($tripPassenger->status !== 'Pending') {
            return back()->withErrors(['error' => 'Your request already marked as Accepted or Rejected.']);
        }

        $tripPassenger->delete();

        return back()->with('success', 'Request successfully deleted.');
    }


    public function updateStatus(Request $request, Trip $trip)
    {

        $trip->passengers()->update([
            'status' => $request->status,
        ]);
    }

}
