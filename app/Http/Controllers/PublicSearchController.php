<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PublicSearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q'      => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:100'],
            'city'   => ['nullable', 'string', 'max:100'],
            'type'   => ['nullable', 'in:jour,nuit'],
        ]);

        $regions = Pharmacy::where('verification_status', 'approved')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        $pharmacies = null;

        // La recherche ne se lance qu'après clic sur le bouton
        if ($request->has('rechercher')) {
            $q    = trim((string) $request->input('q'));
            $type = $request->input('type', 'jour');

            // Stock > 0 d'un médicament dont le nom correspond
            $stockFilter = fn ($stock) => $stock
                ->where('declared_quantity', '>', 0)
                ->whereHas('medicament', fn ($m) => $m
                    ->where('is_active', true)
                    ->where('name', 'like', "%{$q}%"));

            $pharmacies = Pharmacy::query()
                ->where('verification_status', 'approved')
                ->where('type', $type)
                ->when($request->filled('region'), fn ($x) => $x->where('region', $request->input('region')))
                ->when($request->filled('city'), fn ($x) => $x->where('city', $request->input('city')))
                ->when($q !== '', fn ($x) => $x
                    ->whereHas('stocks', $stockFilter)
                    ->with(['stocks' => fn ($s) => $stockFilter($s)->with('medicament')]))
                ->orderBy('name')
                ->limit(100)
                ->get();
        }

        return view('public.search', compact('regions', 'pharmacies'));
    }

    // Liste des villes/délégations d'un gouvernorat (2e liste déroulante)
    public function cities(Request $request)
    {
        return Pharmacy::where('verification_status', 'approved')
            ->where('region', $request->query('region'))
            ->whereColumn('city', '!=', 'region')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
    }

    // Suggestions de noms de médicaments pendant la saisie
    public function medicaments(Request $request)
    {
        $q = trim((string) $request->query('q'));

        if (mb_strlen($q) < 2) {
            return [];
        }

        return Medicament::where('is_active', true)
            ->where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');
    }
}