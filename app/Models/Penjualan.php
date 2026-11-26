<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'panen_id',
        'tanggal_penjualan',
        'pembeli',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penjualan' => 'date',
            'jumlah' => 'decimal:2',
            'harga_satuan' => 'decimal:2',
            'total_harga' => 'decimal:2',
        ];
    }

    public function panen(): BelongsTo
    {
        return $this->belongsTo(Panen::class, 'panen_id');
    }

    public function transaksiKeuangans(): HasMany
    {
        return $this->hasMany(TransaksiKeuangan::class, 'penjualan_id');
    }
}
