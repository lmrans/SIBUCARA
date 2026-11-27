<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanPertanianExport implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle
{
    /**
     * @param  Collection<int, array<string, string|float|int|null>>  $baris
     */
    public function __construct(public Collection $baris) {}

    public function collection(): Collection
    {
        return $this->baris->map(fn (array $baris): array => [
            $baris['jenis'],
            $baris['tanggal'],
            $baris['keterangan'],
            $baris['jumlah'],
            $baris['satuan'],
            $baris['total'],
        ]);
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Jenis', 'Tanggal', 'Keterangan', 'Jumlah', 'Satuan', 'Total'];
    }

    public function title(): string
    {
        return 'Laporan Pertanian';
    }
}
