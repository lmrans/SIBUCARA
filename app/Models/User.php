<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var array<string, string>
     */
    public const ROLES = [
        'pemilik' => 'Pemilik',
        'mandor' => 'Mandor',
        'pekerja' => 'Pekerja',
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'role',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pertanianPemilik(): HasMany
    {
        return $this->hasMany(Pertanian::class, 'pemilik_id');
    }

    public function keanggotaan(): HasMany
    {
        return $this->hasMany(Membership::class, 'user_id');
    }

    public function pertanianYangDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(Pertanian::class, 'memberships')
            ->withPivot(['divalidasi_oleh', 'status', 'tanggal_pengajuan', 'tanggal_verifikasi'])
            ->withTimestamps();
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }

    // Pemeriksaan role pengguna
    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

    public function isMandor(): bool
    {
        return $this->role === 'mandor';
    }

    public function isPekerja(): bool
    {
        return $this->role === 'pekerja';
    }
}
