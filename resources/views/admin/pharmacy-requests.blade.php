
@extends('layouts.admin')

@section('title', 'Demandes de pharmacies - PharmaFind')

@section('content')

<div class="mb-4">
    <h1 class="page-title">Demandes de création</h1>
    <p class="page-subtitle">
        Consultez les demandes des pharmaciens et examinez leurs justificatifs.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-x-circle-fill me-2"></i>
        {{ session('error') }}
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between">
            <strong>Demandes en attente</strong>
            <span class="badge bg-danger">
                {{ $pharmacies->total() }} demande(s)
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th class="ps-4">Pharmacie</th>
                    <th>Responsable</th>
                    <th>Ville</th>
                    <th>Date de demande</th>
                    <th>Statut</th>
                    <th class="text-end pe-4">Action</th>
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

                        <td>
                            {{ $pharmacy->user->full_name ?? '—' }}
                        </td>

                        <td>{{ $pharmacy->city }}</td>

                        <td>
                            {{ $pharmacy->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                En attente
                            </span>
                        </td>

                        <td class="text-end pe-4">
                            <a href="{{ route('admin.pharmacies.show', $pharmacy) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>
                                Examiner
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">
                                Aucune demande en attente.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    @if($pharmacies->hasPages())
        <div class="card-footer bg-white">
            {{ $pharmacies->links() }}
        </div>
    @endif
</div>

@endsection
