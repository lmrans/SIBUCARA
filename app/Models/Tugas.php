<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    public const PRIORITAS = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
    ];

    /**
     * @var array<string, string>
     */
    public const STATUSES = [
        'terjadwal' => 'Terjadwal',
        'berlangsung' => 'Berlangsung',
        'selesai' => 'Selesai',
    ];

    protected $fillable = [
        'budidaya_id',
        'dibuat_oleh',
        'ditugaskan_ke',
        'judul_tugas',
        'deskripsi',
        'prioritas',
        'status',
    ];

    public function budidaya(): BelongsTo
    {
        return $this->belongsTo(KegiatanBudidaya::class, 'budidaya_id');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function ditugaskanKe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditugaskan_ke');
    }

    public function jadwalPekerjaans(): HasMany
    {
        return $this->hasMany(JadwalPekerjaan::class, 'tugas_id');
    }

    public function laporanPekerjaans(): HasMany
    {
        return $this->hasMany(LaporanPekerjaan::class, 'tugas_id');
    }
}
