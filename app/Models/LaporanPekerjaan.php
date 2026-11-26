<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanPekerjaan extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    public const STATUS_VALIDASI = [
        'menunggu' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'revisi' => 'Revisi',
    ];

    protected $fillable = [
        'tugas_id',
        'pekerja_id',
        'divalidasi_oleh',
        'tanggal_laporan',
        'deskripsi',
        'status_validasi',
        'catatan_validasi',
        'tanggal_validasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_laporan' => 'date',
            'tanggal_validasi' => 'date',
        ];
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function pekerja(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pekerja_id');
    }

    public function divalidasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    public function buktiPekerjaans(): HasMany
    {
        return $this->hasMany(BuktiPekerjaan::class, 'laporan_id');
    }
}
