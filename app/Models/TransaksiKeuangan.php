<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    public const JENIS = [
        'pemasukan' => 'Pemasukan',
        'pengeluaran' => 'Pengeluaran',
    ];

    protected $fillable = [
        'pertanian_id',
        'user_id',
        'penjualan_id',
        'jenis',
        'kategori',
        'jumlah',
        'tanggal_transaksi',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_transaksi' => 'date',
        ];
    }

    public function pertanian(): BelongsTo
    {
        return $this->belongsTo(Pertanian::class, 'pertanian_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
