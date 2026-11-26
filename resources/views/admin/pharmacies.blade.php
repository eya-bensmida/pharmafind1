
@extends('layouts.admin')

@section('title', 'Pharmacies approuvées - PharmaFind')

@section('content')

<div class="mb-4">
    <h1 class="page-title">Pharmacies</h1>
    <p class="page-subtitle">
        Liste des pharmacies dont les demandes ont été approuvées.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pharmacies.index') }}">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Nom, ville ou adresse..."
                    value="{{ $search ?? '' }}"
                >

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <strong>Pharmacies approuvées</strong>
        <span class="badge bg-success ms-2">
            {{ $pharmacies->count() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Pharmacie</th>
                    <th>Responsable</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pharmacies as $pharmacy)
                    <tr>
                        <td class="ps-4">
                            <strong>{{ $pharmacy->name }}</strong>
                            <div class="small text-muted">
                                {{ $pharmacy->address }}
                            </div>
                        </td>

                        <td>{{ $pharmacy->user->full_name ?? '—' }}</td>
                        <td>{{ $pharmacy->city }}</td>
                        <td>{{ $pharmacy->phone ?? '—' }}</td>

                        <td>
                            <span class="badge bg-success">
                                Approuvée
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            Aucune pharmacie approuvée pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
