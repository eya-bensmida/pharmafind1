<?php

namespace App\Http\Controllers;

use App\Models\FailedSearch;

class FailedSearchController extends Controller
{
    public function index()
    {
        // Une ligne par médicament introuvable, du plus recherché au moins recherché
        $failedSearches = FailedSearch::query()
            ->select(
                'search_term as term',
                'search_count as total',
                'last_searched_at as last_at'
            )
            ->orderByDesc('search_count')
            ->orderByDesc('last_searched_at')
            ->paginate(15);

        return view('admin.failed-searches', compact('failedSearches'));
    }
}