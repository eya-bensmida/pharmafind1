<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class PharmacistDashboardController extends Controller
{
    // En dessous (ou égal) de ce seuil, le stock est considéré comme faible
    private const LOW_STOCK = 5;

    public function index()
    {
        $pharmacy = auth()->user()->pharmacy;

        if (! $pharmacy || ! $pharmacy->is_approved) {
            abort(403, "Votre pharmacie n'est pas encore approuvée.");
        }

        $stocks = $pharmacy->stocks()->with('medicament')->get();

        $total = $stocks->count();
        $units = (int) $stocks->sum('declared_quantity');

        $out = $stocks->filter(fn ($s) => $s->declared_quantity <= 0)->count();
        $low = $stocks->filter(fn ($s) => $s->declared_quantity > 0 && $s->declared_quantity <= self::LOW_STOCK)->count();
        $available = $total - $out - $low;

        $health = $total > 0 ? (int) round(($available / $total) * 100) : 0;

        // Références non mises à jour depuis plus de 30 jours
        $stale = $stocks->filter(fn ($s) => ! $s->updated_stock_at || $s->updated_stock_at->lt(now()->subDays(30)))->count();

        $recent = $stocks
            ->sortByDesc(fn ($s) => optional($s->updated_stock_at)->timestamp ?? 0)
            ->take(6)
            ->values();

        $alerts = $stocks
            ->filter(fn ($s) => $s->declared_quantity <= self::LOW_STOCK)
            ->sortBy('declared_quantity')
            ->take(6)
            ->values();

        $top = $stocks->sortByDesc('declared_quantity')->take(6)->values();

        $chartLabels = $top->map(fn ($s) => Str::limit($s->medicament->name, 22))->values();
        $chartValues = $top->map(fn ($s) => $s->declared_quantity)->values();

        return view('pharmacien.dashboard', [
            'pharmacy'    => $pharmacy,
            'total'       => $total,
            'units'       => $units,
            'available'   => $available,
            'low'         => $low,
            'out'         => $out,
            'health'      => $health,
            'stale'       => $stale,
            'recent'      => $recent,
            'alerts'      => $alerts,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ]);
    }
}
