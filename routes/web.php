<?php

use App\Http\Controllers\Mandor\DashboardController as MandorDashboardController;
use App\Http\Controllers\Mandor\KegiatanController;
use App\Http\Controllers\Pekerja\DashboardController as PekerjaDashboardController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\PenggunaController;
use App\Http\Controllers\Pemilik\PertanianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

            Route::resource('pengguna', PenggunaController::class)
                ->only(['index', 'show', 'edit', 'update', 'destroy']);

            Route::resource('pertanian', PertanianController::class)
                ->except(['destroy']);
        });

    // Dashboard Mandor
    Route::prefix('mandor')
        ->name('mandor.')
        ->group(function () {
            Route::get('/dashboard', [MandorDashboardController::class, 'index'])
                ->name('dashboard');

            // Kegiatan budidaya
            Route::resource('kegiatan', KegiatanController::class);
        });

    // Dashboard Pekerja
    Route::prefix('pekerja')
        ->name('pekerja.')
        ->group(function () {
            Route::get('/dashboard', [PekerjaDashboardController::class, 'index'])
                ->name('dashboard');
        });
});

require __DIR__.'/auth.php';
