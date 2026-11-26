<?php

namespace App\Http\Controllers\Mandor;

use App\Http\Controllers\Controller;
use App\Models\KegiatanBudidaya;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KegiatanController extends Controller
{
    /**
     * Aturan validasi bersama untuk penyimpanan dan pembaruan kegiatan.
     *
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(): array
    {
        return [
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(KegiatanBudidaya::STATUSES))],
        ];
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isMandor(), 403);

        $kegiatans = KegiatanBudidaya::query()
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10);

        return view('mandor.kegiatan.index', [
            'kegiatans' => $kegiatans,
            'statuses' => KegiatanBudidaya::STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isMandor(), 403);

        return view('mandor.kegiatan.create', ['statuses' => KegiatanBudidaya::STATUSES]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isMandor(), 403);

        KegiatanBudidaya::create($request->validate($this->validationRules()));

        return redirect()->route('mandor.kegiatan.index')
            ->with('success', 'Kegiatan budidaya berhasil ditambahkan.');
    }

    public function show(Request $request, KegiatanBudidaya $kegiatan): View
    {
        abort_unless($request->user()->isMandor(), 403);

        return view('mandor.kegiatan.show', [
            'kegiatan' => $kegiatan,
            'statuses' => KegiatanBudidaya::STATUSES,
        ]);
    }

    public function edit(Request $request, KegiatanBudidaya $kegiatan): View
    {
        abort_unless($request->user()->isMandor(), 403);

        return view('mandor.kegiatan.edit', [
            'kegiatan' => $kegiatan,
            'statuses' => KegiatanBudidaya::STATUSES,
        ]);
    }

    public function update(Request $request, KegiatanBudidaya $kegiatan): RedirectResponse
    {
        abort_unless($request->user()->isMandor(), 403);

        $kegiatan->update($request->validate($this->validationRules()));

        return redirect()->route('mandor.kegiatan.index')
            ->with('success', 'Kegiatan budidaya berhasil diperbarui.');
    }

    public function destroy(Request $request, KegiatanBudidaya $kegiatan): RedirectResponse
    {
        abort_unless($request->user()->isMandor(), 403);

        $kegiatan->delete();

        return redirect()->route('mandor.kegiatan.index')
            ->with('success', 'Kegiatan budidaya berhasil dihapus.');
    }
}
