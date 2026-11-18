
@extends('layouts.admin')

@section('title', 'Vue d’ensemble - PharmaFind')

@section('content')

<div class="mb-4">
    <h1 class="page-title">Vue d'ensemble</h1>
    <p class="page-subtitle">
        Bienvenue dans votre espace d'administration PharmaFind.
    </p>
</div>

<div class="row g-4">

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2">Utilisateurs</p>
                    <h3 class="fw-bold mb-0">--</h3>
                </div>
                <i class="bi bi-people fs-2" style="color:#16A6A0"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2">Médicaments</p>
                    <h3 class="fw-bold mb-0">--</h3>
                </div>
                <i class="bi bi-capsule fs-2" style="color:#16A6A0"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2">Propositions</p>
                    <h3 class="fw-bold mb-0">--</h3>
                </div>
                <i class="bi bi-clipboard-check fs-2" style="color:#16A6A0"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2">Recherches sans résultat</p>
                    <h3 class="fw-bold mb-0">--</h3>
                </div>
                <i class="bi bi-search fs-2" style="color:#16A6A0"></i>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-2">Bienvenue sur PharmaFind</h5>
        <p class="text-muted mb-0">
            Gérez les utilisateurs, le catalogue des médicaments,
            les propositions et les recherches sans résultat depuis
            votre espace d'administration.
        </p>
    </div>
</div>

@endsection
