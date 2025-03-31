<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('car/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'model' => ['required'],
            'color' => ['required'],
            'licenseplate' => ['required'],
            'year' => ['required', 'numeric'],
        ]);

        $car = Car::create([
            'user_id' => Auth::id(),
            'model' => $request->model,
            'color' => $request->color,
            'license_plate' => $request->licenseplate,
            'year' => $request->year,
        ]);

        return redirect(route('car.show', $car->id));

    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        dd($car);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }
}
