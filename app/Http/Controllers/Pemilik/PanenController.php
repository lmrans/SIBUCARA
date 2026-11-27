<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Panen;
use App\Models\Pertanian;
use App\Models\Tanaman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class PanenController extends Controller
{
    /**
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(int $pemilikId): array
    {
        $pertanianIds = Pertanian::where('pemilik_id', $pemilikId)->pluck('id')->all();

        return [
            'tanaman_id' => ['required', Rule::exists('tanamen', 'id')->whereIn('pertanian_id', $pertanianIds)],
            'tanggal_panen' => ['required', 'date'],
            'jumlah' => ['required', 'numeric', 'min:0.01'],
            'satuan' => ['required', 'string', 'max:30'],
            'kualitas' => ['required', 'string', 'max:30'],
        ];
    }

    private function abortIfNotOwned(Request $request, Panen $panen): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($panen->tanaman?->pertanian?->pemilik_id === $request->user()->id, 404);
    }

    /**
     * @return Collection<int, Tanaman>
     */
    private function tanamanOptions(int $pemilikId): Collection
    {
        return Tanaman::whereHas('pertanian', fn ($q) => $q->where('pemilik_id', $pemilikId))
            ->orderBy('nama_tanaman')
            ->get(['id', 'nama_tanaman']);
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $panens = Panen::query()
            ->whereHas('tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id))
            ->with('tanaman.pertanian')
            ->latest('tanggal_panen')
            ->paginate(10);

        return view('pemilik.panen.index', ['panens' => $panens]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        return view('pemilik.panen.create', ['tanamen' => $this->tanamanOptions($request->user()->id)]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        $validated = $request->validate($this->validationRules($request->user()->id));
        $panen = Panen::create($validated);

        Notifikasi::create([
            'user_id' => $request->user()->id,
            'judul' => 'Hasil panen dicatat',
            'pesan' => 'Data hasil panen '.($panen->tanaman->nama_tanaman ?? 'tanaman').' berhasil dicatat.',
            'jenis' => 'panen',
            'status_baca' => 'belum_dibaca',
        ]);

        return redirect()->route('pemilik.panen.index')
            ->with('success', 'Data panen berhasil ditambahkan.');
    }

    public function show(Request $request, Panen $panen): View
    {
        $this->abortIfNotOwned($request, $panen);

        return view('pemilik.panen.show', ['panen' => $panen->load('tanaman.pertanian')]);
    }

    public function edit(Request $request, Panen $panen): View
    {
        $this->abortIfNotOwned($request, $panen);

        return view('pemilik.panen.edit', [
            'panen' => $panen,
            'tanamen' => $this->tanamanOptions($request->user()->id),
        ]);
    }

    public function update(Request $request, Panen $panen): RedirectResponse
    {
        $this->abortIfNotOwned($request, $panen);

        $panen->update($request->validate($this->validationRules($request->user()->id)));

        return redirect()->route('pemilik.panen.index')
            ->with('success', 'Data panen berhasil diperbarui.');
    }

    public function destroy(Request $request, Panen $panen): RedirectResponse
    {
        $this->abortIfNotOwned($request, $panen);

        $panen->delete();

        return redirect()->route('pemilik.panen.index')
            ->with('success', 'Data panen berhasil dihapus.');
    }
}
