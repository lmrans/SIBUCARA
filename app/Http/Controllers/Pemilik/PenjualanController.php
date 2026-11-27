<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Panen;
use App\Models\Penjualan;
use App\Models\TransaksiKeuangan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PenjualanController extends Controller
{
    /**
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(int $pemilikId): array
    {
        $panenIds = Panen::whereHas('tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $pemilikId))
            ->pluck('id')
            ->all();

        return [
            'panen_id' => ['required', Rule::exists('panens', 'id')->whereIn('id', $panenIds)],
            'tanggal_penjualan' => ['required', 'date'],
            'pembeli' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'numeric', 'min:0.01'],
            'harga_satuan' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    private function abortIfNotOwned(Request $request, Penjualan $penjualan): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless(
            $penjualan->panen?->tanaman?->pertanian?->pemilik_id === $request->user()->id,
            404
        );
    }

    /**
     * @return Collection<int, Panen>
     */
    private function panenOptions(int $pemilikId): Collection
    {
        return Panen::whereHas('tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $pemilikId))
            ->with('tanaman')
            ->orderByDesc('tanggal_panen')
            ->get(['id', 'tanaman_id', 'tanggal_panen']);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function dataPenjualan(array $validated): array
    {
        return [
            'panen_id' => $validated['panen_id'],
            'tanggal_penjualan' => $validated['tanggal_penjualan'],
            'pembeli' => $validated['pembeli'],
            'jumlah' => $validated['jumlah'],
            'harga_satuan' => $validated['harga_satuan'],
            'total_harga' => (float) $validated['jumlah'] * (float) $validated['harga_satuan'],
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function syncTransaksiKeuangan(Penjualan $penjualan, array $validated, Request $request): void
    {
        $transaksi = $penjualan->transaksiKeuangans()->first();
        $data = [
            'pertanian_id' => $penjualan->panen->tanaman->pertanian_id,
            'user_id' => $request->user()->id,
            'penjualan_id' => $penjualan->id,
            'jenis' => 'pemasukan',
            'kategori' => 'Penjualan hasil panen',
            'jumlah' => $penjualan->total_harga,
            'tanggal_transaksi' => $validated['tanggal_penjualan'],
            'keterangan' => 'Penjualan hasil panen kepada '.$validated['pembeli'],
        ];

        if ($transaksi) {
            $transaksi->update($data);

            return;
        }

        TransaksiKeuangan::create($data);
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $penjualans = Penjualan::query()
            ->whereHas('panen.tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id))
            ->with('panen.tanaman.pertanian')
            ->latest('tanggal_penjualan')
            ->paginate(10);

        return view('pemilik.penjualan.index', ['penjualans' => $penjualans]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        return view('pemilik.penjualan.create', [
            'panens' => $this->panenOptions($request->user()->id),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        $validated = $request->validate($this->validationRules($request->user()->id));

        $penjualan = DB::transaction(function () use ($validated, $request) {
            $penjualan = Penjualan::create($this->dataPenjualan($validated));
            $this->syncTransaksiKeuangan($penjualan, $validated, $request);

            return $penjualan;
        });

        return redirect()->route('pemilik.penjualan.index')
            ->with('success', 'Data penjualan berhasil ditambahkan.');
    }

    public function show(Request $request, Penjualan $penjualan): View
    {
        $this->abortIfNotOwned($request, $penjualan);

        return view('pemilik.penjualan.show', [
            'penjualan' => $penjualan->load('panen.tanaman.pertanian'),
        ]);
    }

    public function edit(Request $request, Penjualan $penjualan): View
    {
        $this->abortIfNotOwned($request, $penjualan);

        return view('pemilik.penjualan.edit', [
            'penjualan' => $penjualan,
            'panens' => $this->panenOptions($request->user()->id),
        ]);
    }

    public function update(Request $request, Penjualan $penjualan): RedirectResponse
    {
        $this->abortIfNotOwned($request, $penjualan);

        $validated = $request->validate($this->validationRules($request->user()->id));

        DB::transaction(function () use ($penjualan, $validated, $request) {
            $penjualan->update($this->dataPenjualan($validated));
            $this->syncTransaksiKeuangan($penjualan, $validated, $request);
        });

        return redirect()->route('pemilik.penjualan.index')
            ->with('success', 'Data penjualan berhasil diperbarui.');
    }

    public function destroy(Request $request, Penjualan $penjualan): RedirectResponse
    {
        $this->abortIfNotOwned($request, $penjualan);

        DB::transaction(function () use ($penjualan) {
            $penjualan->transaksiKeuangans()->delete();
            $penjualan->delete();
        });

        return redirect()->route('pemilik.penjualan.index')
            ->with('success', 'Data penjualan berhasil dihapus.');
    }
}
