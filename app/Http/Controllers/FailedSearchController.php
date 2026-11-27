<?php

namespace App\Http\Controllers;

use App\Models\FailedSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class FailedSearchController extends Controller
{
    /**
     * Noms de colonnes possibles pour le terme recherché.
     * Le contrôleur utilise la première qui existe dans la table.
     */
    private const TERM_CANDIDATES = [
        'query',
        'search_query',
        'search_term',
        'term',
        'keyword',
        'keywords',
        'medicament_name',
        'medicine_name',
        'medicament',
        'medicine',
        'name',
        'nom',
        'search',
        'text',
    ];

    public function index(Request $request)
    {
        $table = (new FailedSearch)->getTable();
        $term  = $this->termColumn($table);

        $hasDate = Schema::hasColumn($table, 'created_at');

        // Regroupement : un médicament introuvable = une ligne
        $failedSearches = FailedSearch::query()->whereNotNull($term)
            ->select(
                $term . ' as term',
                DB::raw('COUNT(*) as total'),
                DB::raw($hasDate ? 'MAX(created_at) as last_at' : 'NULL as last_at')
            )
            ->groupBy($term)
            ->orderByDesc('total')
            ->orderByDesc($hasDate ? 'last_at' : 'term')
            ->paginate(15)
            ->withQueryString();

        return view('admin.failed-searches', compact('failedSearches'));
    }

    /**
     * Trouve la colonne qui contient le terme recherché.
     */
    private function termColumn(string $table): string
    {
        $columns = Schema::getColumnListing($table);

        foreach (self::TERM_CANDIDATES as $candidate) {
            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }

        throw new RuntimeException(
            "Impossible de trouver la colonne du terme recherché dans la table "
            . "'{$table}'. Colonnes disponibles : " . implode(', ', $columns)
            . ". Ajoute le bon nom dans TERM_CANDIDATES du contrôleur."
        );
    }
}