<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengajuanMandorController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $pengajuans = Membership::query()
            ->where('status', 'menunggu')
            ->whereHas('pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id))
            ->with(['user', 'pertanian'])
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        return view('pemilik.pengajuan-mandor.index', ['pengajuans' => $pengajuans]);
    }

    public function show(Request $request, Membership $pengajuanMandor): View
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($pengajuanMandor->pertanian?->pemilik_id === $request->user()->id, 404);

        return view('pemilik.pengajuan-mandor.show', [
            'pengajuan' => $pengajuanMandor->load(['user', 'pertanian']),
        ]);
    }

    public function update(Request $request, Membership $pengajuanMandor): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($pengajuanMandor->pertanian?->pemilik_id === $request->user()->id, 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['disetujui', 'ditolak'])],
        ]);

        $pengajuanMandor->update([
            'status' => $validated['status'],
            'divalidasi_oleh' => $request->user()->id,
            'tanggal_verifikasi' => now()->toDateString(),
        ]);

        return redirect()->route('pemilik.pengajuan-mandor.index')
            ->with('success', 'Pengajuan Mandor berhasil '.$validated['status'].'.');
    }
}
