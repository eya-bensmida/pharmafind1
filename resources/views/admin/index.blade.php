
@extends('layouts.admin')

@section('title', 'Gestion des pharmacies')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Gestion des pharmacies</h1>
        <p class="page-subtitle">
            Consultez et gérez les pharmacies inscrites sur PharmaFind.
        </p>
    </div>
</div>

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="text-muted small">Total des pharmacies</div>
            <div class="fs-3 fw-bold">{{ $pharmacies->total() }}</div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <form method="GET"
              action="{{ route('admin.pharmacies.index') }}"
              class="row g-2 mb-4">

            <div class="col-md-9">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Rechercher par nom, ville ou adresse..."
                    value="{{ $search ?? '' }}"
                >
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-teal w-100">
                    <i class="bi bi-search me-1"></i>
                    Rechercher
                </button>

                <a href="{{ route('admin.pharmacies.index') }}"
                   class="btn btn-light">
                    Réinitialiser
                </a>
            </div>

        </form>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Pharmacie</th>
                        <th>Ville</th>
                        <th>Téléphone</th>
                        <th>Responsable</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($pharmacies as $pharmacy)

                    <tr>
                        <td>
                            <strong>{{ $pharmacy->name }}</strong>
                            <div class="small text-muted">
                                {{ $pharmacy->address }}
                            </div>
                        </td>

                        <td>{{ $pharmacy->city }}</td>

                        <td>{{ $pharmacy->phone ?? 'Non renseigné' }}</td>

                        <td>{{ $pharmacy->user->name ?? 'Inconnu' }}</td>

                        <td>
                            @if($pharmacy->is_approved)
                                <span class="badge bg-success">Approuvée</span>
                            @else
                                <span class="badge bg-warning text-dark">En attente / désactivée</span>
                            @endif
                        </td>

                        <td class="text-end">

                            @if(!$pharmacy->is_approved)

                                <form method="POST"
                                      action="{{ route('admin.pharmacies.approve', $pharmacy) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-sm btn-success"
                                            type="submit">
                                        Approuver
                                    </button>
                                </form>

                            @else

                                <form method="POST"
                                      action="{{ route('admin.pharmacies.reject', $pharmacy) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-sm btn-outline-danger"
                                            type="submit">
                                        Désactiver
                                    </button>
                                </form>

                            @endif

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Aucune pharmacie trouvée.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $pharmacies->links() }}
        </div>

    </div>
</div>

@endsection
