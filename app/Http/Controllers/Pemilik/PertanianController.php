<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\KegiatanBudidaya;
use App\Models\Panen;
use App\Models\Penjualan;
use App\Models\Pertanian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PertanianController extends Controller
{
    /**
     * Aturan validasi bersama untuk penyimpanan dan pembaruan pertanian.
     *
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(): array
    {
        return [
            'nama_pertanian' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:255'],
            'luas_lahan' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    /**
     * Daftar data terkait yang menghalangi penghapusan pertanian.
     *
     * @return array<int, string>
     */
    private function relatedDataLabels(Pertanian $pertanian): array
    {
        $terkait = [];

        if ($pertanian->memberships()->exists()) {
            $terkait[] = 'anggota';
        }

        if ($pertanian->tanamen()->exists()) {
            $terkait[] = 'tanaman';
        }

        if ($pertanian->stoks()->exists()) {
            $terkait[] = 'stok';
        }

        if ($pertanian->transaksiKeuangans()->exists()) {
            $terkait[] = 'transaksi keuangan';
        }

        if (KegiatanBudidaya::whereHas('tanaman', fn ($q) => $q->where('pertanian_id', $pertanian->id))->exists()) {
            $terkait[] = 'kegiatan budidaya';
        }

        if (Panen::whereHas('tanaman', fn ($q) => $q->where('pertanian_id', $pertanian->id))->exists()) {
            $terkait[] = 'panen';
        }

        if (Penjualan::whereHas('panen.tanaman', fn ($q) => $q->where('pertanian_id', $pertanian->id))->exists()) {
            $terkait[] = 'penjualan';
        }

        return $terkait;
    }

    public function index(Request $request): View
    {
        $pemilikId = $request->user()->id;
        abort_unless($request->user()->isPemilik(), 403);

        $pertanians = Pertanian::query()
            ->where('pemilik_id', $pemilikId)
            ->withCount(['memberships as pekerja_count' => fn ($query) => $query->where('status', 'disetujui')])
            ->orderBy('nama_pertanian')
            ->paginate(10);

        $ringkasan = [
            'total_pertanian' => Pertanian::where('pemilik_id', $pemilikId)->count(),
            'total_luas' => Pertanian::where('pemilik_id', $pemilikId)->sum('luas_lahan'),
            'lahan_aktif' => Pertanian::where('pemilik_id', $pemilikId)
                ->whereHas('memberships', fn ($query) => $query->where('status', 'disetujui'))
                ->count(),
            'total_pekerja' => Pertanian::where('pemilik_id', $pemilikId)
                ->whereHas('memberships', fn ($query) => $query->where('status', 'disetujui'))
                ->withCount(['memberships as pekerja_count' => fn ($query) => $query->where('status', 'disetujui')])
                ->get()
                ->sum('pekerja_count'),
        ];

        return view('pemilik.pertanian.index', [
            'pertanians' => $pertanians,
            'ringkasan' => $ringkasan,
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        return view('pemilik.pertanian.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        Pertanian::create($request->validate($this->validationRules()) + ['pemilik_id' => $request->user()->id]);

        return redirect()->route('pemilik.pertanian.index')
            ->with('success', 'Data pertanian berhasil ditambahkan.');
    }

    public function show(Request $request, Pertanian $pertanian): View
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($pertanian->pemilik_id === $request->user()->id, 404);

        return view('pemilik.pertanian.show', ['pertanian' => $pertanian]);
    }

    public function edit(Request $request, Pertanian $pertanian): View
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($pertanian->pemilik_id === $request->user()->id, 404);

        return view('pemilik.pertanian.edit', ['pertanian' => $pertanian]);
    }

    public function update(Request $request, Pertanian $pertanian): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($pertanian->pemilik_id === $request->user()->id, 404);

        $pertanian->update($request->validate($this->validationRules()));

        return redirect()->route('pemilik.pertanian.index')
            ->with('success', 'Data pertanian berhasil diperbarui.');
    }

    public function destroy(Request $request, Pertanian $pertanian): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($pertanian->pemilik_id === $request->user()->id, 404);

        $terkait = $this->relatedDataLabels($pertanian);

        if ($terkait !== []) {
            return redirect()->route('pemilik.pertanian.index')
                ->with('error', 'Pertanian tidak dapat dihapus karena masih memiliki data: '.implode(', ', $terkait).'.');
        }

        $pertanian->delete();

        return redirect()->route('pemilik.pertanian.index')
            ->with('success', 'Data pertanian berhasil dihapus.');
    }
}
