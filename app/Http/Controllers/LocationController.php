<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $apiKey = config('services.google_maps.api_key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Google Maps API key is not configured.'
            ], 500);
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => "{$request->lat},{$request->lng}",
            'key' => $apiKey,
        ]);

        if ($response->failed()) {
            logger()->error('Google Maps API error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return response()->json([
                'error' => 'Error with Google Maps API communication'
            ], 500);
        }

        $data = $response->json();

        logger()->debug('Google Maps response', [
            'latlng' => "{$request->lat},{$request->lng}",
            'response' => $data
        ]);

        $address = $data['results'][0]['formatted_address'] ?? 'Location not found';

        return response()->json([
            'address' => $address
        ]);
    }


}
