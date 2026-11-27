<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\JadwalPekerjaan;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class JadwalController extends Controller
{
    /**
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(int $pemilikId): array
    {
        $tugasIds = $this->tugasOptions($pemilikId)->pluck('id')->all();

        return [
            'tugas_id' => ['required', Rule::exists('tugas', 'id')->whereIn('id', $tugasIds)],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    private function abortIfNotOwned(Request $request, JadwalPekerjaan $jadwal): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless(
            $jadwal->tugas?->budidaya?->tanaman?->pertanian?->pemilik_id === $request->user()->id,
            404
        );
    }

    /**
     * @return Collection<int, Tugas>
     */
    private function tugasOptions(int $pemilikId): Collection
    {
        return Tugas::whereHas('budidaya.tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $pemilikId))
            ->with('budidaya.tanaman.pertanian')
            ->orderBy('judul_tugas')
            ->get(['id', 'judul_tugas', 'budidaya_id']);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function dataJadwal(array $validated): array
    {
        $data = [
            'tugas_id' => $validated['tugas_id'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'].':00',
            'waktu_selesai' => filled($validated['waktu_selesai'] ?? null)
                ? $validated['waktu_selesai'].':00'
                : null,
        ];

        if (array_key_exists('keterangan', $validated)) {
            $data['keterangan'] = $validated['keterangan'];
        }

        return $data;
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $jadwals = JadwalPekerjaan::query()
            ->whereHas('tugas.budidaya.tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id))
            ->with('tugas.budidaya.tanaman.pertanian')
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->paginate(10);

        return view('pemilik.jadwal.index', ['jadwals' => $jadwals]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        return view('pemilik.jadwal.create', [
            'tugasOptions' => $this->tugasOptions($request->user()->id),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        JadwalPekerjaan::create($this->dataJadwal($request->validate($this->validationRules($request->user()->id))));

        return redirect()->route('pemilik.jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil ditambahkan.');
    }

    public function show(Request $request, JadwalPekerjaan $jadwal): View
    {
        $this->abortIfNotOwned($request, $jadwal);

        return view('pemilik.jadwal.show', [
            'jadwal' => $jadwal->load('tugas.budidaya.tanaman.pertanian'),
        ]);
    }

    public function edit(Request $request, JadwalPekerjaan $jadwal): View
    {
        $this->abortIfNotOwned($request, $jadwal);

        return view('pemilik.jadwal.edit', [
            'jadwal' => $jadwal,
            'tugasOptions' => $this->tugasOptions($request->user()->id),
        ]);
    }

    public function update(Request $request, JadwalPekerjaan $jadwal): RedirectResponse
    {
        $this->abortIfNotOwned($request, $jadwal);

        $jadwal->update($this->dataJadwal($request->validate($this->validationRules($request->user()->id))));

        return redirect()->route('pemilik.jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil diperbarui.');
    }
}
