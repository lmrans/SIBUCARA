<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\KegiatanBudidaya;
use App\Models\Pertanian;
use App\Models\Tanaman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class BudidayaController extends Controller
{
    /**
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(int $pemilikId): array
    {
        $pertanianIds = Pertanian::where('pemilik_id', $pemilikId)->pluck('id')->all();

        return [
            'tanaman_id' => ['required', Rule::exists('tanamen', 'id')->whereIn('pertanian_id', $pertanianIds)],
            'nama_kegiatan' => ['required', 'string', 'max:100'],
            'tanggal' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(KegiatanBudidaya::STATUSES))],
        ];
    }

    private function abortIfNotOwned(Request $request, KegiatanBudidaya $kegiatan): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($kegiatan->tanaman?->pertanian?->pemilik_id === $request->user()->id, 404);
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

        $kegiatans = KegiatanBudidaya::query()
            ->whereHas('tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id))
            ->with('tanaman.pertanian')
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('pemilik.budidaya.index', ['kegiatans' => $kegiatans]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        return view('pemilik.budidaya.create', [
            'tanamen' => $this->tanamanOptions($request->user()->id),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        KegiatanBudidaya::create($request->validate($this->validationRules($request->user()->id)));

        return redirect()->route('pemilik.budidaya.index')
            ->with('success', 'Kegiatan budidaya berhasil ditambahkan.');
    }

    public function show(Request $request, KegiatanBudidaya $budidaya): View
    {
        $this->abortIfNotOwned($request, $budidaya);

        return view('pemilik.budidaya.show', [
            'kegiatan' => $budidaya->load('tanaman.pertanian'),
        ]);
    }

    public function edit(Request $request, KegiatanBudidaya $budidaya): View
    {
        $this->abortIfNotOwned($request, $budidaya);

        return view('pemilik.budidaya.edit', [
            'kegiatan' => $budidaya,
            'tanamen' => $this->tanamanOptions($request->user()->id),
        ]);
    }

    public function update(Request $request, KegiatanBudidaya $budidaya): RedirectResponse
    {
        $this->abortIfNotOwned($request, $budidaya);

        $budidaya->update($request->validate($this->validationRules($request->user()->id)));

        return redirect()->route('pemilik.budidaya.index')
            ->with('success', 'Kegiatan budidaya berhasil diperbarui.');
    }
}
