<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pertanian;
use App\Models\TransaksiKeuangan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class KeuanganController extends Controller
{
    /**
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(int $pemilikId): array
    {
        return [
            'pertanian_id' => ['required', Rule::exists('pertanians', 'id')->where('pemilik_id', $pemilikId)],
            'jenis' => ['required', Rule::in(array_keys(TransaksiKeuangan::JENIS))],
            'kategori' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'numeric', 'min:0.01'],
            'tanggal_transaksi' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
        ];
    }

    /**
     * @return Collection<int, Pertanian>
     */
    private function pertanianOptions(int $pemilikId): Collection
    {
        return Pertanian::where('pemilik_id', $pemilikId)
            ->orderBy('nama_pertanian')
            ->get(['id', 'nama_pertanian']);
    }

    private function abortIfNotOwned(Request $request, TransaksiKeuangan $transaksi): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($transaksi->pertanian?->pemilik_id === $request->user()->id, 404);
    }

    /**
     * @return array<string, float>
     */
    private function ringkasan(Request $request): array
    {
        $pertanianIds = Pertanian::where('pemilik_id', $request->user()->id)->pluck('id');

        $pemasukan = (float) TransaksiKeuangan::whereIn('pertanian_id', $pertanianIds)
            ->where('jenis', 'pemasukan')
            ->sum('jumlah');
        $pengeluaran = (float) TransaksiKeuangan::whereIn('pertanian_id', $pertanianIds)
            ->where('jenis', 'pengeluaran')
            ->sum('jumlah');

        return [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $pemasukan - $pengeluaran,
        ];
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $transaksis = TransaksiKeuangan::query()
            ->whereHas('pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id))
            ->with('pertanian')
            ->latest('tanggal_transaksi')
            ->paginate(10);

        return view('pemilik.keuangan.index', [
            'transaksis' => $transaksis,
            'ringkasan' => $this->ringkasan($request),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        return view('pemilik.keuangan.create', [
            'pertanians' => $this->pertanianOptions($request->user()->id),
            'jenis' => TransaksiKeuangan::JENIS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        $validated = $request->validate($this->validationRules($request->user()->id));
        $validated['user_id'] = $request->user()->id;

        TransaksiKeuangan::create($validated);

        return redirect()->route('pemilik.keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil ditambahkan.');
    }

    public function show(Request $request, TransaksiKeuangan $keuangan): View
    {
        $this->abortIfNotOwned($request, $keuangan);

        return view('pemilik.keuangan.show', [
            'transaksi' => $keuangan->load('pertanian'),
        ]);
    }

    public function edit(Request $request, TransaksiKeuangan $keuangan): View
    {
        $this->abortIfNotOwned($request, $keuangan);

        return view('pemilik.keuangan.edit', [
            'transaksi' => $keuangan,
            'pertanians' => $this->pertanianOptions($request->user()->id),
            'jenis' => TransaksiKeuangan::JENIS,
        ]);
    }

    public function update(Request $request, TransaksiKeuangan $keuangan): RedirectResponse
    {
        $this->abortIfNotOwned($request, $keuangan);

        $validated = $request->validate($this->validationRules($request->user()->id));
        $validated['user_id'] = $request->user()->id;

        $keuangan->update($validated);

        return redirect()->route('pemilik.keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil diperbarui.');
    }

    public function destroy(Request $request, TransaksiKeuangan $keuangan): RedirectResponse
    {
        $this->abortIfNotOwned($request, $keuangan);

        $keuangan->delete();

        return redirect()->route('pemilik.keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil dihapus.');
    }
}
