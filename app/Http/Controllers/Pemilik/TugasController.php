<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function show(Request $request, Tugas $tugas): View
    {
        abort_unless($request->user()->isPemilik(), 403);
        abort_unless(
            $tugas->budidaya?->tanaman?->pertanian?->pemilik_id === $request->user()->id,
            404
        );

        return view('pemilik.tugas.show', [
            'tugas' => $tugas->load([
                'budidaya.tanaman.pertanian',
                'ditugaskanKe',
                'jadwalPekerjaans',
                'laporanPekerjaans',
            ]),
        ]);
    }
}
