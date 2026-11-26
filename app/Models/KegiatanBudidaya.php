<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KegiatanBudidaya extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    public const STATUSES = [
        'rencana' => 'Rencana',
        'berlangsung' => 'Berlangsung',
        'selesai' => 'Selesai',
    ];

    protected $fillable = [
        'tanaman_id',
        'nama_kegiatan',
        'tanggal',
        'deskripsi',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function tanaman(): BelongsTo
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'budidaya_id');
    }
}
