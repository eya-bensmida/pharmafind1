
@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')

<div class="mb-4">
    <h1 class="page-title">Gestion des utilisateurs</h1>
    <p class="page-subtitle">
        Consultez et suivez les comptes inscrits sur PharmaFind.
    </p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="text-muted small">Total utilisateurs</div>
                <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="text-muted small">Comptes actifs</div>
                <h2 class="fw-bold text-success mb-0">{{ $activeUsers }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="text-muted small">En attente</div>
                <h2 class="fw-bold text-warning mb-0">{{ $pendingUsers }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="text-muted small">Comptes suspendus</div>
                <h2 class="fw-bold text-danger mb-0">{{ $suspendedUsers }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        <form method="GET" action="{{ route('admin.users.index') }}"
              class="row g-2 mb-4">

            <div class="col-lg-5">
                <input type="text" name="q" class="form-control"
                       placeholder="Nom ou adresse e-mail..."
                       value="{{ request('q') }}">
            </div>

            <div class="col-lg-3">
                <select name="role" class="form-select">
                    <option value="">Tous les rôles</option>
                    <option value="admin" @selected(request('role') == 'admin')>Administrateur</option>
                    <option value="citizen" @selected(request('role') == 'citizen')>Citoyen</option>
                    <option value="pharmacist" @selected(request('role') == 'pharmacist')>Pharmacien</option>
                </select>
            </div>

            <div class="col-lg-2">
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="active" @selected(request('status') == 'active')>Actif</option>
                    <option value="pending" @selected(request('status') == 'pending')>En attente</option>
                    <option value="suspended" @selected(request('status') == 'suspended')>Suspendu</option>
                </select>
            </div>

            <div class="col-lg-2 d-grid">
                <button type="submit" class="btn btn-teal">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Utilisateur</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>

                            <td>{{ $user->phone ?? 'Non renseigné' }}</td>

                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        Administrateur
                                        @break
                                    @case('citizen')
                                        Citoyen
                                        @break
                                    @case('pharmacist')
                                        Pharmacien
                                        @break
                                    @default
                                        {{ ucfirst($user->role) }}
                                @endswitch
                            </td>

                            <td>
                                @switch($user->account_status)
                                    @case('active')
                                        <span class="badge bg-success">Actif</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                        @break
                                    @case('suspended')
                                        <span class="badge bg-danger">Suspendu</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($user->account_status) }}
                                        </span>
                                @endswitch
                            </td>

                            <td>
                                {{ $user->created_at
                                    ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y')
                                    : 'Non renseignée' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Aucun utilisateur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection
