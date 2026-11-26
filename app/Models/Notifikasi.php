<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    public const STATUS_BACA = [
        'belum_dibaca' => 'Belum Dibaca',
        'dibaca' => 'Dibaca',
    ];

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'jenis',
        'status_baca',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
