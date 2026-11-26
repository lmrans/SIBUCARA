<?php

namespace Database\Factories;

use App\Models\KegiatanBudidaya;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tugas>
 */
class TugasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'budidaya_id' => KegiatanBudidaya::factory(),
            'dibuat_oleh' => User::factory()->mandor(),
            'ditugaskan_ke' => User::factory()->pekerja(),
            'judul_tugas' => fake()->sentence(3),
            'deskripsi' => fake()->sentence(),
            'prioritas' => fake()->randomElement(array_keys(Tugas::PRIORITAS)),
            'status' => 'terjadwal',
        ];
    }
}
