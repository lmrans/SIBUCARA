<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    use HasFactory;

    protected $fillable = [
        'pertanian_id',
        'nama_barang',
        'jenis_barang',
        'jumlah',
        'satuan',
        'tanggal_masuk',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_masuk' => 'date',
        ];
    }

    public function pertanian(): BelongsTo
    {
        return $this->belongsTo(Pertanian::class, 'pertanian_id');
    }
}
