<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitoyenSearchController extends Controller
{
    public function search(Request $request)
    {
        $validated = $request->validate([
            'medicament' => ['nullable', 'string', 'max:150'],
            'gouvernorat' => ['nullable', 'string', 'max:100'],
            'ville' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $query = Pharmacy::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if (!empty($validated['gouvernorat'])) {
            $query->where('region', 'LIKE', '%' . $validated['gouvernorat'] . '%');
        }

        if (!empty($validated['ville'])) {
            $query->where('city', 'LIKE', '%' . $validated['ville'] . '%');
        }

        if (!empty($validated['medicament'])) {
            $keyword = $validated['medicament'];

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('address', 'LIKE', '%' . $keyword . '%');
            });
        }

        $hasLocation = isset($validated['latitude'], $validated['longitude']);

        if ($hasLocation) {
            $latitude = (float) $validated['latitude'];
            $longitude = (float) $validated['longitude'];

            $query->select('pharmacies.*')
                ->selectRaw(
                    '(6371 * ACOS(LEAST(1, GREATEST(-1,
                        COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                        COS(RADIANS(longitude) - RADIANS(?)) +
                        SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                    )))) AS distance',
                    [$latitude, $longitude, $latitude]
                )
                ->orderBy('distance');
        } else {
            $query->orderBy('name');
        }

        $pharmacies = $query->paginate(12)->withQueryString();

        return view('citoyen.results', [
            'pharmacies' => $pharmacies,
            'hasLocation' => $hasLocation,
            'search' => $validated,
        ]);
    }
}
