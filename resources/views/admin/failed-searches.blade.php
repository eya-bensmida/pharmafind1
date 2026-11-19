
@extends('layouts.admin')

@section('title', 'Recherches sans résultat')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Recherches infructueuses</h1>
        <p class="page-subtitle">
            Consultez les médicaments recherchés sans résultat.
        </p>
    </div>
</div>

<div class="row g-3 mb-4">

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="text-muted small mb-2">
                    Nombre de recherches enregistrées
                </div>
                <h2 class="fw-bold mb-0">
                    {{ $totalSearches }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="text-muted small mb-2">
                    Nombre total de tentatives
                </div>
                <h2 class="fw-bold mb-0">
                    {{ $totalAttempts }}
                </h2>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <form method="GET"
              action="{{ route('admin.failed-searches.index') }}"
              class="row g-2 mb-4">

            <div class="col-md-9">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Rechercher un médicament..."
                    value="{{ request('q') }}"
                >
            </div>

            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-teal">
                    <i class="bi bi-search me-2"></i>
                    Rechercher
                </button>
            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Médicament recherché</th>
                        <th>Utilisateur</th>
                        <th>Tentatives</th>
                        <th>Dernière recherche</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($failedSearches as $search)

                        <tr>
                            <td class="fw-semibold">
                                {{ $search->search_term }}
                            </td>

                            <td>
                                @if($search->user_name)
                                    <div>{{ $search->user_name }}</div>
                                    <small class="text-muted">
                                        {{ $search->user_email }}
                                    </small>
                                @else
                                    <span class="text-muted">
                                        Utilisateur inconnu
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $search->search_count }}
                                </span>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($search->last_searched_at)->format('d/m/Y H:i') }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-search fs-2 text-muted"></i>

                                <p class="text-muted mt-2 mb-0">
                                    Aucune recherche infructueuse trouvée.
                                </p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $failedSearches->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection
