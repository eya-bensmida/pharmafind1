<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\Medicament;
use Illuminate\Http\Request;

class CitoyenSearchController extends Controller
{
    /**
     * Afficher le formulaire de recherche.
     */
    public function form()
    {
        $gouvernorats = Pharmacy::query()
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return view('citoyen.search-form', [
            'gouvernorats' => $gouvernorats,
        ]);
    }

    /**
     * Charger les villes/localités d'un gouvernorat.
     */
    public function localities(Request $request)
    {
        $validated = $request->validate([
            'gouvernorat' => ['required', 'string', 'max:100'],
        ]);

        $localites = Pharmacy::query()
            ->where('region', $validated['gouvernorat'])
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return response()->json($localites);
    }

    /**
     * Effectuer la recherche et afficher les résultats.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'medicament' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:jour,nuit'],
            'gouvernorat' => ['nullable', 'string', 'max:100'],
            'ville' => ['nullable', 'string', 'max:100'],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
                'required_with:longitude',
            ],
            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
                'required_with:latitude',
            ],
        ]);

        $validated['medicament'] = trim($validated['medicament']);

        // 1. Rechercher uniquement les médicaments actifs.
        $medicaments = Medicament::query()
            ->where('is_active', true)
            ->where('name', 'LIKE', '%' . $validated['medicament'] . '%')
            ->get();

        $medicamentIds = $medicaments->pluck('id');

        $medicamentFound = $medicaments->isNotEmpty();

        // 2. Déterminer si les coordonnées GPS sont disponibles.
        $hasLocation = isset(
            $validated['latitude'],
            $validated['longitude']
        );

        // 3. Construire la recherche des pharmacies.
        $query = Pharmacy::query()
            ->where('verification_status', 'approved')
            ->where('is_approved', true)
            ->where('type', $validated['type'])

            // Pharmacies ayant le médicament en stock.
            ->whereHas('stocks', function ($stockQuery) use ($medicamentIds) {
                $stockQuery
                    ->whereIn('medicament_id', $medicamentIds)
                    ->where('declared_quantity', '>', 0);
            })

            // Charger uniquement les stocks correspondants.
            ->with(['stocks' => function ($stockQuery) use ($medicamentIds) {
                $stockQuery
                    ->whereIn('medicament_id', $medicamentIds)
                    ->where('declared_quantity', '>', 0)
                    ->with('medicament');
            }]);

        // 4. MODE GPS : priorité à la proximité.
        if ($hasLocation) {
            $latitude = (float) $validated['latitude'];
            $longitude = (float) $validated['longitude'];

            $query->whereNotNull('latitude')
                ->whereNotNull('longitude');

            $query->select('pharmacies.*')
                ->selectRaw(
                    '(6371 * ACOS(LEAST(1, GREATEST(-1,
                        COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                        COS(RADIANS(longitude) - RADIANS(?)) +
                        SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                    )))) AS distance',
                    [$latitude, $longitude, $latitude]
                )
                ->havingRaw('distance <= ?', [10])
                ->orderBy('distance', 'asc');
        }

        // 5. MODE MANUEL : gouvernorat et ville.
        else {
            if (!empty($validated['gouvernorat'])) {
                $query->where('region', $validated['gouvernorat']);
            }

            if (!empty($validated['ville'])) {
                $query->where('city', $validated['ville']);
            }

            $query->orderBy('name');
        }

        // 6. Paginer les résultats.
        $pharmacies = $query
            ->paginate(12)
            ->withQueryString();

        return view('citoyen.results', [
            'pharmacies' => $pharmacies,
            'hasLocation' => $hasLocation,
            'search' => $validated,
            'medicaments' => $medicaments,
            'medicamentFound' => $medicamentFound,
        ]);
    }
}
