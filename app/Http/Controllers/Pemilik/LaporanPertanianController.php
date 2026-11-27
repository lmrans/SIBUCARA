<?php

namespace App\Http\Controllers\Pemilik;

use App\Exports\LaporanPertanianExport;
use App\Http\Controllers\Controller;
use App\Models\Panen;
use App\Models\Penjualan;
use App\Models\Pertanian;
use App\Models\TransaksiKeuangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaporanPertanianController extends Controller
{
    /**
     * @return array<string, string|null>
     */
    private function periode(Request $request): array
    {
        $validated = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        return [
            'dari' => $validated['dari'] ?? null,
            'sampai' => $validated['sampai'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function dataLaporan(Request $request): array
    {
        abort_unless($request->user()->isPemilik(), 403);

        $periode = $this->periode($request);
        $pertanianIds = Pertanian::where('pemilik_id', $request->user()->id)->pluck('id');

        $panens = Panen::query()
            ->whereHas('tanaman', fn ($q) => $q->whereIn('pertanian_id', $pertanianIds))
            ->with('tanaman.pertanian')
            ->when($periode['dari'], fn ($q) => $q->whereDate('tanggal_panen', '>=', $periode['dari']))
            ->when($periode['sampai'], fn ($q) => $q->whereDate('tanggal_panen', '<=', $periode['sampai']))
            ->orderBy('tanggal_panen')
            ->get(['id', 'tanaman_id', 'tanggal_panen', 'jumlah', 'satuan', 'kualitas']);

        $penjualans = Penjualan::query()
            ->whereHas('panen.tanaman', fn ($q) => $q->whereIn('pertanian_id', $pertanianIds))
            ->with('panen.tanaman.pertanian')
            ->when($periode['dari'], fn ($q) => $q->whereDate('tanggal_penjualan', '>=', $periode['dari']))
            ->when($periode['sampai'], fn ($q) => $q->whereDate('tanggal_penjualan', '<=', $periode['sampai']))
            ->orderBy('tanggal_penjualan')
            ->get(['id', 'panen_id', 'tanggal_penjualan', 'pembeli', 'jumlah', 'harga_satuan', 'total_harga']);

        $transaksis = TransaksiKeuangan::query()
            ->whereIn('pertanian_id', $pertanianIds)
            ->with('pertanian')
            ->when($periode['dari'], fn ($q) => $q->whereDate('tanggal_transaksi', '>=', $periode['dari']))
            ->when($periode['sampai'], fn ($q) => $q->whereDate('tanggal_transaksi', '<=', $periode['sampai']))
            ->orderBy('tanggal_transaksi')
            ->get(['id', 'pertanian_id', 'jenis', 'kategori', 'jumlah', 'tanggal_transaksi', 'keterangan']);

        return [
            'periode' => $periode,
            'panens' => $panens,
            'penjualans' => $penjualans,
            'transaksis' => $transaksis,
            'pemasukan' => (float) $transaksis->where('jenis', 'pemasukan')->sum('jumlah'),
            'pengeluaran' => (float) $transaksis->where('jenis', 'pengeluaran')->sum('jumlah'),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, array<string, string|float|int|null>>
     */
    private function barisExport(array $data): Collection
    {
        $baris = $data['panens']->map(fn (Panen $panen): array => [
            'jenis' => 'Panen',
            'tanggal' => $panen->tanggal_panen?->toDateString(),
            'keterangan' => $panen->tanaman->nama_tanaman.' - '.$panen->kualitas,
            'jumlah' => (float) $panen->jumlah,
            'satuan' => $panen->satuan,
            'total' => null,
        ]);

        $baris = $baris->concat($data['penjualans']->map(fn (Penjualan $penjualan): array => [
            'jenis' => 'Penjualan',
            'tanggal' => $penjualan->tanggal_penjualan?->toDateString(),
            'keterangan' => $penjualan->pembeli,
            'jumlah' => (float) $penjualan->jumlah,
            'satuan' => null,
            'total' => (float) $penjualan->total_harga,
        ]));

        return $baris->concat($data['transaksis']->map(fn (TransaksiKeuangan $transaksi): array => [
            'jenis' => 'Keuangan - '.($transaksi->jenis === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran'),
            'tanggal' => $transaksi->tanggal_transaksi?->toDateString(),
            'keterangan' => $transaksi->kategori.' - '.$transaksi->keterangan,
            'jumlah' => (float) $transaksi->jumlah,
            'satuan' => null,
            'total' => null,
        ]));
    }

    public function index(Request $request): View
    {
        return view('pemilik.laporan-pertanian.index', $this->dataLaporan($request));
    }

    public function pdf(Request $request): Response
    {
        $data = $this->dataLaporan($request);
        $data['baris'] = $this->barisExport($data);

        return Pdf::loadView('pemilik.laporan-pertanian.pdf', $data)
            ->download('laporan-pertanian-'.now()->format('Y-m-d').'.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $data = $this->dataLaporan($request);

        return Excel::download(
            new LaporanPertanianExport($this->barisExport($data)),
            'laporan-pertanian-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
