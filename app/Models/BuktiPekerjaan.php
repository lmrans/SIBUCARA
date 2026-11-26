<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'nama_file',
        'path_file',
        'tipe_file',
    ];

    public function laporanPekerjaan(): BelongsTo
    {
        return $this->belongsTo(LaporanPekerjaan::class, 'laporan_id');
    }
}
