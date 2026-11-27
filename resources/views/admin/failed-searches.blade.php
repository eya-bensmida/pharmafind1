@extends('layouts.admin')

@section('title', 'Recherches sans résultat')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="page-title">Recherches sans résultat</h1>
        <p class="page-subtitle mb-0">
            Médicaments recherchés par les citoyens et introuvables dans les pharmacies.
        </p>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>
        Retour au tableau de bord
    </a>
</div>

{{-- TABLEAU --}}
<div class="bg-white border rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">#</th>
                    <th>Médicament recherché</th>
                    <th class="text-center">Nombre de recherches</th>
                    <th>Dernière recherche</th>
                </tr>
            </thead>

            <tbody>
                @forelse($failedSearches as $index => $item)
                    <tr>
                        <td class="text-muted">
                            {{ $failedSearches->firstItem() + $index }}
                        </td>

                        <td class="fw-semibold">
                            <i class="bi bi-capsule text-muted me-2"></i>
                            {{ $item->term }}
                        </td>

                        <td class="text-center">
                            <span class="badge rounded-pill text-bg-{{ $item->total >= 5 ? 'danger' : ($item->total >= 3 ? 'warning' : 'secondary') }}">
                                {{ $item->total }}
                            </span>
                        </td>

                        <td class="text-muted small">
                            @if($item->last_at)
                                {{ \Carbon\Carbon::parse($item->last_at)->format('d/m/Y à H:i') }}
                                <span class="d-block">
                                    {{ \Carbon\Carbon::parse($item->last_at)->diffForHumans() }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-check-circle fs-3 d-block mb-2"></i>
                            Aucune recherche infructueuse pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($failedSearches->hasPages())
        <div class="p-3 border-top">
            {{ $failedSearches->links() }}
        </div>
    @endif
</div>

@endsection