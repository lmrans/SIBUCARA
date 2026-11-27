<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pertanian;
use App\Models\TransaksiKeuangan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InformasiKeuanganController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $pertanianIds = Pertanian::where('pemilik_id', $request->user()->id)->pluck('id');

        $pemasukan = (float) TransaksiKeuangan::whereIn('pertanian_id', $pertanianIds)
            ->where('jenis', 'pemasukan')
            ->sum('jumlah');
        $pengeluaran = (float) TransaksiKeuangan::whereIn('pertanian_id', $pertanianIds)
            ->where('jenis', 'pengeluaran')
            ->sum('jumlah');

        $transaksis = TransaksiKeuangan::query()
            ->whereIn('pertanian_id', $pertanianIds)
            ->with('pertanian')
            ->latest('tanggal_transaksi')
            ->paginate(10);

        return view('pemilik.informasi-keuangan.index', [
            'transaksis' => $transaksis,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $pemasukan - $pengeluaran,
        ]);
    }
}
