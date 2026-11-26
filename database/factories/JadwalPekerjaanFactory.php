<?php

namespace Database\Factories;

use App\Models\JadwalPekerjaan;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JadwalPekerjaan>
 */
class JadwalPekerjaanFactory extends Factory
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
            'tanggal' => fake()->date(),
            'waktu_mulai' => '07:00:00',
            'waktu_selesai' => '11:00:00',
            'keterangan' => fake()->sentence(),
        ];
    }
}
