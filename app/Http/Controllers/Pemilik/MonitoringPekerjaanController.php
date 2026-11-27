<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MonitoringPekerjaanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $query = Tugas::query()
            ->whereHas('budidaya.tanaman.pertanian', fn ($q) => $q->where('pemilik_id', $request->user()->id));

        $ringkasan = [
            'semua' => (clone $query)->count(),
            'terjadwal' => (clone $query)->where('status', 'terjadwal')->count(),
            'berlangsung' => (clone $query)->where('status', 'berlangsung')->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
        ];

        $tugas = $query
            ->with(['budidaya.tanaman.pertanian', 'ditugaskanKe', 'jadwalPekerjaans'])
            ->latest()
            ->paginate(10);

        return view('pemilik.monitoring-pekerjaan.index', [
            'tugas' => $tugas,
            'ringkasan' => $ringkasan,
        ]);
    }
}
