<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    private function abortIfNotOwned(Request $request, Notifikasi $notifikasi): void
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless($notifikasi->user_id === $request->user()->id, 404);
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $notifikasis = Notifikasi::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('pemilik.notifikasi.index', [
            'notifikasis' => $notifikasis,
            'belumDibaca' => Notifikasi::where('user_id', $request->user()->id)
                ->where('status_baca', 'belum_dibaca')
                ->count(),
        ]);
    }

    public function update(Request $request, Notifikasi $notifikasi): RedirectResponse
    {
        $this->abortIfNotOwned($request, $notifikasi);

        $notifikasi->update(['status_baca' => 'dibaca']);

        return redirect()->route('pemilik.notifikasi.index')
            ->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function tandaiSemuaDibaca(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isPemilik(), 403);

        Notifikasi::where('user_id', $request->user()->id)
            ->where('status_baca', 'belum_dibaca')
            ->update(['status_baca' => 'dibaca']);

        return redirect()->route('pemilik.notifikasi.index')
            ->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
