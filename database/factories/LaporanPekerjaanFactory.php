<?php

namespace Database\Factories;

use App\Models\LaporanPekerjaan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaporanPekerjaan>
 */
class LaporanPekerjaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tugas_id' => Tugas::factory(),
            'pekerja_id' => User::factory()->pekerja(),
            'divalidasi_oleh' => null,
            'tanggal_laporan' => fake()->date(),
            'deskripsi' => fake()->paragraph(),
            'status_validasi' => 'menunggu',
            'catatan_validasi' => null,
            'tanggal_validasi' => null,
        ];
    }
}
