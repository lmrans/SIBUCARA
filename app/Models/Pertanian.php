<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertanian extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemilik_id',
        'nama_pertanian',
        'lokasi',
        'luas_lahan',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'luas_lahan' => 'decimal:2',
        ];
    }

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'pertanian_id');
    }

    public function tanamen(): HasMany
    {
        return $this->hasMany(Tanaman::class, 'pertanian_id');
    }

    public function stoks(): HasMany
    {
        return $this->hasMany(Stok::class, 'pertanian_id');
    }

    public function transaksiKeuangans(): HasMany
    {
        return $this->hasMany(TransaksiKeuangan::class, 'pertanian_id');
    }
}
