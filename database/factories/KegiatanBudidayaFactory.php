<?php

namespace Database\Factories;

use App\Models\KegiatanBudidaya;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KegiatanBudidaya>
 */
class KegiatanBudidayaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kegiatan' => fake()->sentence(3),
            'tanggal' => fake()->date(),
            'deskripsi' => fake()->sentence(),
            'status' => fake()->randomElement(array_keys(KegiatanBudidaya::STATUSES)),
        ];
    }
}
