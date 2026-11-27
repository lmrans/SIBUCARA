<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Laporan Pengelolaan Pertanian') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 6px 0; }
        h2 { font-size: 13px; margin: 18px 0 6px 0; }
        p { margin: 0 0 6px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; }
        .summary { margin-bottom: 14px; }
        .summary td { border: none; padding: 2px 12px 2px 0; }
    </style>
</head>
<body>
    <h1>{{ __('Laporan Pengelolaan Pertanian') }}</h1>
    <p>
        {{ __('Periode') }}:
        {{ $periode['dari'] ?? '-' }} s/d {{ $periode['sampai'] ?? '-' }}
    </p>

    <table class="summary">
        <tr>
            <td><strong>{{ __('Pemasukan') }}</strong>: {{ number_format($pemasukan, 2, ',', '.') }}</td>
            <td><strong>{{ __('Pengeluaran') }}</strong>: {{ number_format($pengeluaran, 2, ',', '.') }}</td>
            <td><strong>{{ __('Saldo') }}</strong>: {{ number_format($pemasukan - $pengeluaran, 2, ',', '.') }}</td>
        </tr>
    </table>

    <h2>{{ __('Rincian Laporan') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('Jenis') }}</th>
                <th>{{ __('Tanggal') }}</th>
                <th>{{ __('Keterangan') }}</th>
                <th>{{ __('Jumlah') }}</th>
                <th>{{ __('Satuan') }}</th>
                <th>{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($baris as $barisan)
                <tr>
                    <td>{{ $barisan['jenis'] }}</td>
                    <td>{{ $barisan['tanggal'] }}</td>
                    <td>{{ $barisan['keterangan'] }}</td>
                    <td>{{ $barisan['jumlah'] }}</td>
                    <td>{{ $barisan['satuan'] }}</td>
                    <td>{{ $barisan['total'] }}</td>
                </tr>
            @empty
                <tr><td colspan="6">{{ __('Tidak ada data pada periode ini.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
