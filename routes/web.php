<?php

use App\Http\Controllers\Mandor\DashboardController as MandorDashboardController;
use App\Http\Controllers\Mandor\KegiatanController;
use App\Http\Controllers\Pekerja\DashboardController as PekerjaDashboardController;
use App\Http\Controllers\Pemilik\BudidayaController;
use App\Http\Controllers\Pemilik\CuacaController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\InformasiKeuanganController;
use App\Http\Controllers\Pemilik\JadwalController;
use App\Http\Controllers\Pemilik\KeuanganController;
use App\Http\Controllers\Pemilik\LaporanController;
use App\Http\Controllers\Pemilik\LaporanPertanianController;
use App\Http\Controllers\Pemilik\MonitoringPekerjaanController;
use App\Http\Controllers\Pemilik\NotifikasiController;
use App\Http\Controllers\Pemilik\PanenController;
use App\Http\Controllers\Pemilik\PengajuanMandorController;
use App\Http\Controllers\Pemilik\PenggunaController;
use App\Http\Controllers\Pemilik\PenjualanController;
use App\Http\Controllers\Pemilik\PertanianController;
use App\Http\Controllers\Pemilik\TanamanController;
use App\Http\Controllers\Pemilik\TugasController;
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

            Route::resource('pertanian', PertanianController::class);

            Route::resource('pengajuan-mandor', PengajuanMandorController::class)
                ->only(['index', 'show', 'update']);

            Route::resource('tanaman', TanamanController::class);

            Route::resource('budidaya', BudidayaController::class)
                ->except(['destroy']);

            Route::resource('jadwal', JadwalController::class)
                ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

            Route::resource('tugas', TugasController::class)
                ->only(['show']);

            Route::get('/monitoring-pekerjaan', [MonitoringPekerjaanController::class, 'index'])
                ->name('monitoring-pekerjaan.index');

            Route::get('/cuaca', [CuacaController::class, 'index'])
                ->name('cuaca.index');

            Route::resource('laporan', LaporanController::class)
                ->only(['index', 'show']);

            Route::get('/laporan-pertanian', [LaporanPertanianController::class, 'index'])
                ->name('laporan-pertanian.index');
            Route::get('/laporan-pertanian/pdf', [LaporanPertanianController::class, 'pdf'])
                ->name('laporan-pertanian.pdf');
            Route::get('/laporan-pertanian/excel', [LaporanPertanianController::class, 'excel'])
                ->name('laporan-pertanian.excel');

            Route::resource('panen', PanenController::class);

            Route::resource('penjualan', PenjualanController::class);

            Route::resource('keuangan', KeuanganController::class);

            Route::get('/informasi-keuangan', [InformasiKeuanganController::class, 'index'])
                ->name('informasi-keuangan.index');

            Route::post('/notifikasi/tandai-semua-dibaca', [NotifikasiController::class, 'tandaiSemuaDibaca'])
                ->name('notifikasi.tandai-semua-dibaca');
            Route::resource('notifikasi', NotifikasiController::class)
                ->only(['index', 'update']);
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
