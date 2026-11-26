<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panen extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanaman_id',
        'tanggal_panen',
        'jumlah',
        'satuan',
        'kualitas',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_panen' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    public function tanaman(): BelongsTo
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'panen_id');
    }
}
