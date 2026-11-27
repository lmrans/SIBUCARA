<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pertanian;
use App\Models\Tanaman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TanamanController extends Controller
{
    /**
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(int $pemilikId): array
    {
        return [
            'pertanian_id' => ['required', Rule::exists('pertanians', 'id')->where('pemilik_id', $pemilikId)],
            'nama_tanaman' => ['required', 'string', 'max:100'],
            'jenis' => ['required', 'string', 'max:100'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal_tanam' => ['required', 'date'],
            'status' => ['required', Rule::in(array_keys(Tanaman::STATUSES))],
        ];
    }

    private function abortIfNotOwned(Request $request, Tanaman $tanaman): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($tanaman->pertanian?->pemilik_id === $request->user()->id, 404);
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $tanamen = Tanaman::query()
            ->whereHas('pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id))
            ->with('pertanian')
            ->orderBy('nama_tanaman')
            ->paginate(10);

        return view('pemilik.tanaman.index', ['tanamen' => $tanamen]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $pertanians = Pertanian::where('pemilik_id', $request->user()->id)
            ->orderBy('nama_pertanian')
            ->get(['id', 'nama_pertanian']);

        return view('pemilik.tanaman.create', ['pertanians' => $pertanians]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        Tanaman::create($request->validate($this->validationRules($request->user()->id)));

        return redirect()->route('pemilik.tanaman.index')
            ->with('success', 'Data tanaman berhasil ditambahkan.');
    }

    public function show(Request $request, Tanaman $tanaman): View
    {
        $this->abortIfNotOwned($request, $tanaman);

        return view('pemilik.tanaman.show', ['tanaman' => $tanaman->load('pertanian')]);
    }

    public function edit(Request $request, Tanaman $tanaman): View
    {
        $this->abortIfNotOwned($request, $tanaman);

        return view('pemilik.tanaman.edit', [
            'tanaman' => $tanaman,
            'pertanians' => Pertanian::where('pemilik_id', $request->user()->id)
                ->orderBy('nama_pertanian')
                ->get(['id', 'nama_pertanian']),
        ]);
    }

    public function update(Request $request, Tanaman $tanaman): RedirectResponse
    {
        $this->abortIfNotOwned($request, $tanaman);

        $tanaman->update($request->validate($this->validationRules($request->user()->id)));

        return redirect()->route('pemilik.tanaman.index')
            ->with('success', 'Data tanaman berhasil diperbarui.');
    }

    public function destroy(Request $request, Tanaman $tanaman): RedirectResponse
    {
        $this->abortIfNotOwned($request, $tanaman);

        $tanaman->delete();

        return redirect()->route('pemilik.tanaman.index')
            ->with('success', 'Data tanaman berhasil dihapus.');
    }
}
