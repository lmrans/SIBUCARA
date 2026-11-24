<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Mandor\DashboardController as MandorDashboardController;
use App\Http\Controllers\Pekerja\DashboardController as PekerjaDashboardController;

// Halaman utama / Landing Page
Route::view('/', 'welcome')->name('home');

// Dashboard berdasarkan role
Route::middleware(['auth'])->group(function () {

    // Pengarah dashboard setelah login
    Route::get('/dashboard', function (Request $request) {

        $user = $request->user();

        return match ($user->role) {
            'pemilik' => redirect()->route('pemilik.dashboard'),
            'mandor' => redirect()->route('mandor.dashboard'),
            'pekerja' => redirect()->route('pekerja.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');

    // Dashboard Pemilik
    Route::prefix('pemilik')
        ->name('pemilik.')
        ->group(function () {
            Route::get('/dashboard', [PemilikDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // Dashboard Mandor
    Route::prefix('mandor')
        ->name('mandor.')
        ->group(function () {
            Route::get('/dashboard', [MandorDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // Dashboard Pekerja
    Route::prefix('pekerja')
        ->name('pekerja.')
        ->group(function () {
            Route::get('/dashboard', [PekerjaDashboardController::class, 'index'])
                ->name('dashboard');
        });
});

require __DIR__ . '/auth.php';
