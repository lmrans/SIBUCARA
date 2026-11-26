<?php

namespace Database\Factories;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notifikasi>
 */
class NotifikasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'judul' => fake()->sentence(3),
            'pesan' => fake()->sentence(),
            'jenis' => fake()->randomElement(['pengajuan', 'panen', 'tugas', 'stok']),
            'status_baca' => 'belum_dibaca',
        ];
    }
}
