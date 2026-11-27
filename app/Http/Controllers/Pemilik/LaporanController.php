<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\LaporanPekerjaan;
use App\Models\Pertanian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LaporanController extends Controller
{
    /**
     * @return Collection<int, int>
     */
    private function ownedPertanianIds(Request $request): Collection
    {
        return Pertanian::where('pemilik_id', $request->user()->id)->pluck('id');
    }

    private function abortIfNotOwned(Request $request, LaporanPekerjaan $laporan): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless(
            $laporan->tugas?->budidaya?->tanaman?->pertanian?->pemilik_id === $request->user()->id,
            404
        );
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $pertanianIds = $this->ownedPertanianIds($request);

        $laporans = LaporanPekerjaan::query()
            ->whereHas('tugas.budidaya.tanaman', fn ($q) => $q->whereIn('pertanian_id', $pertanianIds))
            ->with(['pekerja', 'tugas.budidaya.tanaman.pertanian'])
            ->latest('tanggal_laporan')
            ->paginate(10);

        return view('pemilik.laporan.index', ['laporans' => $laporans]);
    }

    public function show(Request $request, LaporanPekerjaan $laporan): View
    {
        $this->abortIfNotOwned($request, $laporan);

        return view('pemilik.laporan.show', [
            'laporan' => $laporan->load(['pekerja', 'buktiPekerjaans', 'tugas.budidaya.tanaman.pertanian']),
        ]);
    }
}
