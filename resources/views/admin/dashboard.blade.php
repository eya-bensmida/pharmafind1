
@extends('layouts.admin')

@section('title', 'Vue d’ensemble - PharmaFind')

@section('content')

<div class="mb-4">
    <h1 class="page-title">Vue d'ensemble</h1>
    <p class="page-subtitle">
        Bienvenue dans votre espace d'administration PharmaFind.
    </p>
</div>

@if($pendingRequests > 0)
    <a href="{{ route('admin.pharmacies.requests') }}"
       class="text-decoration-none">

        <div class="alert alert-danger d-flex align-items-center justify-content-between shadow-sm mb-4"
             style="border-left:5px solid #dc3545;">

            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-2"></i>

                <div>
                    <h5 class="fw-bold mb-1">
                        Nouvelles demandes de pharmacies !
                    </h5>

                    <p class="mb-0">
                      Des demandes de pharmacies sont en attente d'examen.
                    </p>
                </div>
            </div>

            <span class="btn btn-danger">
                Examiner les demandes
                <i class="bi bi-arrow-right ms-2"></i>
            </span>
        </div>
    </a>
@else
    <div class="alert alert-success mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        Aucune nouvelle demande de pharmacie à examiner.
    </div>
@endif

<div class="row g-4">

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <p class="text-muted mb-2">Utilisateurs</p>
            <h3 class="fw-bold">{{ $usersCount }}</h3>
            <i class="bi bi-people fs-2 text-info"></i>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <p class="text-muted mb-2">Pharmacies approuvées</p>
            <h3 class="fw-bold">{{ $approvedPharmacies }}</h3>
            <i class="bi bi-hospital fs-2 text-success"></i>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <p class="text-muted mb-2">Médicaments</p>
            <h3 class="fw-bold">{{ $medicamentsCount }}</h3>
            <i class="bi bi-capsule fs-2 text-primary"></i>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <p class="text-muted mb-2">Propositions</p>
            <h3 class="fw-bold">{{ $proposalsCount }}</h3>
            <i class="bi bi-clipboard-check fs-2 text-warning"></i>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body p-4">
        <h5 class="fw-bold">Bienvenue sur PharmaFind</h5>
        <p class="text-muted mb-0">
            Gérez les pharmacies, les demandes d'inscription,
            les médicaments et les propositions depuis
            votre espace d'administration.
        </p>
    </div>
</div>

@endsection
