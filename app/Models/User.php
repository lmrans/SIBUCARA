<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
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
