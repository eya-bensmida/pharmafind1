
@extends('layouts.admin')

@section('title', 'Gestion des pharmacies - PharmaFind')

@section('content')
<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Gestion des pharmacies</h2>
            <p class="text-muted mb-0">
                Consultez, recherchez et gérez les pharmacies inscrites.
            </p>
        </div>
        <span class="badge bg-primary fs-6">
            {{ $pharmacies->total() }} pharmacies
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacies.index') }}">
                <div class="row g-2">
                    <div class="col-md-10">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Rechercher par nom, ville ou adresse..."
                            value="{{ $search ?? '' }}"
                        >
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Pharmacie</th>
                            <th>Responsable</th>
                            <th>Ville</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pharmacies as $pharmacy)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">
                                        {{ $pharmacy->name }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $pharmacy->address }}
                                    </small>
                                </td>

                                <td>
                                    {{ $pharmacy->user->name ?? 'Non renseigné' }}
                                </td>

                                <td>
                                    {{ $pharmacy->city ?? '—' }}
                                </td>

                                <td>
                                    {{ $pharmacy->phone ?? '—' }}
                                </td>

                                <td>
                                    @if($pharmacy->is_approved)
                                        <span class="badge bg-success-subtle text-success">
                                            Approuvée
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis">
                                            Non approuvée
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if(!$pharmacy->is_approved)
                                            <form method="POST"
                                                  action="{{ route('admin.pharmacies.approve', $pharmacy) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        title="Approuver">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                  action="{{ route('admin.pharmacies.reject', $pharmacy) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Désactiver">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-hospital fs-1 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">
                                        Aucune pharmacie trouvée.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($pharmacies->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $pharmacies->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
