<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
