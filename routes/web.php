
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PharmacyController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/failed-searches', [AdminController::class, 'failedSearches'])
        ->name('admin.failed-searches.index');

    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users.index');

    Route::get('/admin/pharmacies', [PharmacyController::class, 'index'])
        ->name('admin.pharmacies.index');

    Route::patch('/admin/pharmacies/{pharmacy}/approve', [PharmacyController::class, 'approve'])
        ->name('admin.pharmacies.approve');

    Route::patch('/admin/pharmacies/{pharmacy}/reject', [PharmacyController::class, 'reject'])
        ->name('admin.pharmacies.reject');
});
