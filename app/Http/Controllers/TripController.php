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
            $query->where('user_id', auth()->id())->select('trip_id', 'status', 'user_id');
        }])
            ->where(function ($query) {
                $query->where('is_started', 0)
                    ->orWhere(function ($query) {
                        $query->where('is_started', 1)
                            ->where(function ($query) {
                                $query->where('user_id', auth()->id())
                                    ->orWhereHas('passengers', function ($q) {
                                        $q->where('user_id', auth()->id());
                                    });
                            });
                    });
            })
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
            'datetime' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateTimeString(),
                'before_or_equal:' . now()->addYear()->toDateTimeString(),
            ],
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
            'departure_time' => Carbon::parse($request->input('datetime')),
        ]);

        return redirect(route('trip.show', $trip->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        if ($trip->is_started) {
            $user = auth()->user();
            $isOwner = $trip->user_id === $user->id;
            $isPassenger = $trip->passengers()->where('user_id', $user->id)->exists();

            if (!$isOwner && !$isPassenger) {
                abort(403);
            }
        }

        $trip->load(['passengers.user']);

        $formattedTrip = [
            'id' => $trip->id,
            'is_started' => $trip->is_started,
            'is_complete' => $trip->is_complete,
            'destination' => json_decode($trip->destination, true),
            'driverLocation' => json_decode($trip->driver_location, true),
            'destination_name' => $trip->destination_name,
            'origin' => $trip->origin,
            'driver_name' => $trip->user->name,
            'price' => $trip->price,
            'departure_time' => $trip->departure_time->format('d.m.Y, H:i'),
            'passengers' => $trip->passengers->map(function ($passenger) {
                return [
                    'id' => $passenger->id,
                    'user_id' => $passenger->user_id,
                    'name' => $passenger->user->name,
                    'status' => $passenger->status,
                    'created_at' => $passenger->created_at->diffForHumans(),
                ];
            }),
            'isDriver' => $trip->user_id === auth()->id(),
            'created_at' => $trip->created_at->diffForHumans(),
            'updated_at' => $trip->updated_at->diffForHumans(),
        ];

        return Inertia::render('trip/Show', [
            'trip' => $formattedTrip,
            'userRequestStatus' => $trip->passengers
                ->firstWhere('user_id', auth()->id())?->status,
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
        if ($trip->user_id !== auth()->id()) {
            abort(403);
        }

        $trip->passengers()->delete();

        $trip->delete();

        return redirect()->route('trip.index')->with('success', 'Trip Deleted');
    }

    public function search(Request $request)
    {
        $trips = Trip::with(['user', 'passengers' => function ($query) {
            $query->where('user_id', auth()->id())->select('trip_id', 'status', 'user_id');
        }])
            ->where(function ($query) {
                $query->where('is_started', 0)
                    ->orWhere(function ($query) {
                        $query->where('is_started', 1)
                            ->where(function ($query) {
                                $query->where('user_id', auth()->id())
                                    ->orWhereHas('passengers', function ($q) {
                                        $q->where('user_id', auth()->id());
                                    });
                            });
                    });
            })
            ->when($request->startingplace, fn ($q) => $q->where('origin', 'LIKE', "%{$request->startingplace}%"))
            ->when($request->destination, fn ($q) => $q->where('destination_name', 'LIKE', "%{$request->destination}%"))
            ->when($request->datetime, function ($q) use ($request) {
                $date = Carbon::parse($request->datetime)->startOfDay();
                $end = Carbon::parse($request->datetime)->endOfDay();

                $q->whereBetween('departure_time', [$date, $end]);
            })
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
            'requestStatus' => $trip->passengers->firstWhere('user_id', auth()->id())->status ?? null,
            'isDriver' => $trip->user_id === auth()->id(),
            'departure_time' => $trip->departure_time->format('d.m.Y, H:i'),
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

        if ($trip->user_id === auth()->id()) {
            abort(403, 'You cannot book your own trip.');
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


    public function updateStatus(Request $request, Trip $trip, TripPassenger $passenger)
    {
        if ($passenger->trip_id !== $trip->id) {
            abort(403);
        }

        $passenger->update([
            'status' => $request->status,
        ]);
    }

    public function tripStart(Trip $trip)
    {
        if($trip->user_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['is_started' => 1]);
    }

    public function tripFinish(Trip $trip)
    {
        if($trip->user_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['is_complete' => 1]);
    }


}
