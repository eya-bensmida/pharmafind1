
@extends('layouts.admin')

@section('title', 'Examen de la demande - PharmaFind')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Examen de la demande</h1>
        <p class="page-subtitle">
            Vérifiez les informations et les documents fournis.
        </p>
    </div>

    <a href="{{ route('admin.pharmacies.requests') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Retour
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<div class="row g-4">

    <div class="col-lg-7">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-hospital me-2"></i>
                    Informations de la pharmacie
                </h5>
            </div>

            <div class="card-body p-4">

                <div class="mb-3">
                    <small class="text-muted">Nom de la pharmacie</small>
                    <h5>{{ $pharmacy->name }}</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted">Adresse</small>
                        <p>{{ $pharmacy->address }}</p>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Ville</small>
                        <p>{{ $pharmacy->city }}</p>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Région</small>
                        <p>{{ $pharmacy->region ?? '—' }}</p>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Téléphone pharmacie</small>
                        <p>{{ $pharmacy->phone ?? '—' }}</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-person me-2"></i>
                    Informations du responsable
                </h5>
            </div>

            <div class="card-body p-4">

                <p>
                    <strong>Nom complet :</strong>
                    {{ $pharmacy->user->full_name }}
                </p>

                <p>
                    <strong>E-mail :</strong>
                    {{ $pharmacy->user->email }}
                </p>

                <p>
                    <strong>Téléphone :</strong>
                    {{ $pharmacy->user->phone ?? '—' }}
                </p>

            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Documents justificatifs
                </h5>
            </div>

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <strong>Autorisation de la pharmacie</strong>
                        <p class="small text-muted mb-0">
                            Premier justificatif transmis.
                        </p>
                    </div>

                    @if($pharmacy->authorization_document_path)
                        <a href="{{ route('admin.pharmacies.document', [
                            'pharmacy' => $pharmacy,
                            'type' => 'authorization'
                        ]) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>
                            Consulter
                        </a>
                    @else
                        <span class="badge bg-danger">Manquant</span>
                    @endif
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Document professionnel du responsable</strong>
                        <p class="small text-muted mb-0">
                            Deuxième justificatif transmis.
                        </p>
                    </div>

                    @if($pharmacy->order_document_path)
                        <a href="{{ route('admin.pharmacies.document', [
                            'pharmacy' => $pharmacy,
                            'type' => 'order'
                        ]) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>
                            Consulter
                        </a>
                    @else
                        <span class="badge bg-danger">Manquant</span>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <div class="col-lg-5">

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold mb-0">
                    Décision administrative
                </h5>
            </div>

            <div class="card-body p-4">

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    Vérifiez les documents et la cohérence des informations
                    avant toute décision.
                </div>

                <p class="small text-muted">
                    Une approbation active immédiatement le compte
                    et autorise le pharmacien à accéder à son espace.
                </p>

                <form method="POST"
                      action="{{ route('admin.pharmacies.approve', $pharmacy) }}"
                      class="mb-3"
                      onsubmit="return confirm('Confirmer l’approbation de cette pharmacie ?')">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-success w-100 py-3">
                        <i class="bi bi-check-circle me-2"></i>
                        Approuver la demande
                    </button>
                </form>

                <hr>

                <form method="POST"
                      action="{{ route('admin.pharmacies.reject', $pharmacy) }}"
                      onsubmit="return confirm('Confirmer le refus de cette demande ?')">

                    @csrf
                    @method('PATCH')

                    <label class="form-label fw-semibold">
                        Motif du refus (facultatif)
                    </label>

                    <textarea name="rejection_reason"
                              class="form-control mb-3"
                              rows="4"
                              placeholder="Expliquez le motif du refus..."></textarea>

                    <button type="submit"
                            class="btn btn-danger w-100 py-3">
                        <i class="bi bi-x-circle me-2"></i>
                        Refuser la demande
                    </button>
                </form>

            </div>
        </div>

    </div>

</div>

@endsection
