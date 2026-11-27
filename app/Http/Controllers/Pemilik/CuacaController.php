<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pertanian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CuacaController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    private function ambilPrakiraan(Pertanian $pertanian): array
    {
        return Cache::remember('pemilik.cuaca.'.$pertanian->id, 600, function () use ($pertanian): array {
            $apiKey = config('services.openweathermap.key');

            if (! filled($apiKey)) {
                return [
                    'status' => 'error',
                    'pesan' => 'API key OpenWeatherMap belum dikonfigurasi.',
                    'prakiraan' => [],
                ];
            }

            if (! filled($pertanian->lokasi)) {
                return [
                    'status' => 'error',
                    'pesan' => 'Lokasi pertanian belum diisi.',
                    'prakiraan' => [],
                ];
            }

            try {
                $response = Http::baseUrl(config('services.openweathermap.base_url'))
                    ->connectTimeout(3)
                    ->timeout(5)
                    ->retry(2, 200)
                    ->get('/forecast', [
                        'q' => $pertanian->lokasi,
                        'appid' => $apiKey,
                        'units' => 'metric',
                    ]);
            } catch (ConnectionException) {
                return [
                    'status' => 'error',
                    'pesan' => 'Informasi cuaca belum dapat ditampilkan.',
                    'prakiraan' => [],
                ];
            }

            if (! $response->successful()) {
                return [
                    'status' => 'error',
                    'pesan' => 'Informasi cuaca belum dapat ditampilkan.',
                    'prakiraan' => [],
                ];
            }

            $prakiraan = collect($response->json('list', []))
                ->take(15)
                ->map(fn (array $item): array => [
                    'waktu' => $item['dt_txt'] ?? null,
                    'suhu' => $item['main']['temp'] ?? null,
                    'kelembapan' => $item['main']['humidity'] ?? null,
                    'curah_hujan' => $item['rain']['3h'] ?? null,
                ])
                ->all();

            return [
                'status' => 'ok',
                'lokasi' => $pertanian->lokasi,
                'prakiraan' => $prakiraan,
            ];
        });
    }

    private function pertanianDipilih(Request $request): ?Pertanian
    {
        if ($request->filled('pertanian_id')) {
            $pertanian = Pertanian::findOrFail($request->integer('pertanian_id'));
            abort_unless($pertanian->pemilik_id === $request->user()->id, 404);

            return $pertanian;
        }

        return Pertanian::where('pemilik_id', $request->user()->id)
            ->whereNotNull('lokasi')
            ->orderBy('nama_pertanian')
            ->first();
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isPemilik(), 403);

        $pertanians = Pertanian::where('pemilik_id', $request->user()->id)
            ->orderBy('nama_pertanian')
            ->get(['id', 'nama_pertanian']);
        $pertanian = $this->pertanianDipilih($request);

        $cuaca = $pertanian
            ? $this->ambilPrakiraan($pertanian)
            : [
                'status' => 'error',
                'pesan' => 'Belum ada pertanian dengan lokasi yang dapat digunakan.',
                'prakiraan' => [],
            ];

        return view('pemilik.cuaca.index', [
            'pertanians' => $pertanians,
            'pertanianDipilih' => $pertanian,
            'cuaca' => $cuaca,
        ]);
    }
}
