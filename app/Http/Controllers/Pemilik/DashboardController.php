<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\KegiatanBudidaya;
use App\Models\Membership;
use App\Models\Notifikasi;
use App\Models\Panen;
use App\Models\Penjualan;
use App\Models\Pertanian;
use App\Models\Stok;
use App\Models\Tanaman;
use App\Models\TransaksiKeuangan;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $pemilikId = $request->user()->id;
        $pertanianIds = Pertanian::where('pemilik_id', $pemilikId)->pluck('id');
        $pertanianTerkait = fn ($query) => $query->whereIn('pertanian_id', $pertanianIds);

        $pemasukan = TransaksiKeuangan::whereIn('pertanian_id', $pertanianIds)
            ->where('jenis', 'pemasukan')
            ->sum('jumlah');
        $pengeluaran = TransaksiKeuangan::whereIn('pertanian_id', $pertanianIds)
            ->where('jenis', 'pengeluaran')
            ->sum('jumlah');

        $ringkasan = [
            'total_pertanian' => $pertanianIds->count(),
            'lahan_aktif' => Pertanian::whereIn('id', $pertanianIds)
                ->whereHas('memberships', fn ($q) => $q->where('status', 'disetujui'))
                ->count(),
            'total_pekerja' => Membership::whereIn('pertanian_id', $pertanianIds)
                ->where('status', 'disetujui')
                ->count(),
            'total_tanaman' => Tanaman::whereIn('pertanian_id', $pertanianIds)
                ->where('status', 'aktif')
                ->count(),
            'total_kegiatan' => KegiatanBudidaya::whereHas('tanaman', $pertanianTerkait)->count(),
            'total_tugas' => Tugas::whereHas('budidaya.tanaman', $pertanianTerkait)->count(),
            'tugas_berlangsung' => Tugas::whereHas('budidaya.tanaman', $pertanianTerkait)
                ->where('status', 'berlangsung')
                ->count(),
            'tugas_selesai' => Tugas::whereHas('budidaya.tanaman', $pertanianTerkait)
                ->where('status', 'selesai')
                ->count(),
            'total_stok' => Stok::whereIn('pertanian_id', $pertanianIds)->count(),
            'total_panen' => Panen::whereHas('tanaman', $pertanianTerkait)->count(),
            'total_penjualan' => Penjualan::whereHas('panen.tanaman', $pertanianTerkait)->count(),
            'notifikasi_belum_dibaca' => Notifikasi::where('user_id', $pemilikId)
                ->where('status_baca', 'belum_dibaca')
                ->count(),
            'saldo' => $pemasukan - $pengeluaran,
        ];

        return view('dashboard.pemilik.index', ['ringkasan' => $ringkasan]);
    }
}
