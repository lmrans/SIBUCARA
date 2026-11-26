<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanaman extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    public const STATUSES = [
        'aktif' => 'Aktif',
        'nonaktif' => 'Nonaktif',
    ];

    protected $table = 'tanamen';

    protected $fillable = [
        'pertanian_id',
        'nama_tanaman',
        'jenis',
        'jumlah',
        'tanggal_tanam',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tanam' => 'date',
        ];
    }

    public function pertanian(): BelongsTo
    {
        return $this->belongsTo(Pertanian::class, 'pertanian_id');
    }

    public function kegiatanBudidayas(): HasMany
    {
        return $this->hasMany(KegiatanBudidaya::class, 'tanaman_id');
    }

    public function panens(): HasMany
    {
        return $this->hasMany(Panen::class, 'tanaman_id');
    }
}
