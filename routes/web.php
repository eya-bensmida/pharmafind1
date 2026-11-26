<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\PharmacistProfileController;
use App\Http\Controllers\PharmacistRegistrationController;
use App\Http\Controllers\CitoyenHomeController;
use App\Http\Controllers\CitoyenSearchController;
use App\Http\Controllers\PharmacistDashboardController;

// Accueil citoyen
Route::get('/', [CitoyenHomeController::class, 'home'])
    ->name('citoyen.home');

// Recherche des pharmacies pour les citoyens
Route::get('/recherche', [CitoyenSearchController::class, 'form'])
    ->name('citoyen.search.form');

Route::get('/recherche/localites', [CitoyenSearchController::class, 'localities'])
    ->name('citoyen.search.localities');

Route::get('/recherche/resultats', [CitoyenSearchController::class, 'search'])
    ->name('citoyen.search.results');

// Connexion et déconnexion
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Inscription d'une pharmacie
Route::get('/inscription-pharmacie',
    [PharmacistRegistrationController::class, 'create']
)->name('pharmacien.register');

// Autocomplétion des pharmacies existantes - AJAX
Route::get('/inscription-pharmacie/recherche',
    [PharmacistRegistrationController::class, 'searchPharmacies']
)->name('pharmacien.register.search');

Route::post('/inscription-pharmacie',
    [PharmacistRegistrationController::class, 'store']
)->name('pharmacien.register.store');

// Administration
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Demandes de création d'espaces pharmacies
    Route::get('/admin/demandes-pharmacies',
        [PharmacyController::class, 'requests']
    )->name('admin.pharmacies.requests');

    Route::get('/admin/demandes-pharmacies/{pharmacy}',
        [PharmacyController::class, 'showRequest']
    )->name('admin.pharmacies.show');

    Route::get('/admin/demandes-pharmacies/{pharmacy}/document/{type}',
        [PharmacyController::class, 'document']
    )->name('admin.pharmacies.document');

    Route::patch('/admin/demandes-pharmacies/{pharmacy}/approve',
        [PharmacyController::class, 'approve']
    )->name('admin.pharmacies.approve');

    Route::patch('/admin/demandes-pharmacies/{pharmacy}/reject',
        [PharmacyController::class, 'reject']
    )->name('admin.pharmacies.reject');

    // Pharmacies déjà approuvées
    Route::get('/admin/pharmacies',
        [PharmacyController::class, 'index']
    )->name('admin.pharmacies.index');
});

// Espace pharmacien
Route::middleware(['auth', 'role:owner'])
    ->prefix('pharmacien')
    ->name('pharmacien.')
    ->group(function () {

        Route::get('/tableau-de-bord',
            [PharmacistDashboardController::class, 'index']
        )->name('dashboard');

        // Recherche intelligente et suggestions
        Route::get('/medicaments/recherche-intelligente',
            [MedicamentController::class, 'searchSuggestions']
        )->name('medicaments.search');

        // Gestion des médicaments
        Route::resource('medicaments', MedicamentController::class)
            ->except(['show']);

        // Profil
        Route::get('/profil',
            [PharmacistProfileController::class, 'edit']
        )->name('profile.edit');

        Route::put('/profil',
            [PharmacistProfileController::class, 'update']
        )->name('profile.update');
    });
