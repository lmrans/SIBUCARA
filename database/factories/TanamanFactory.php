<?php

namespace Database\Factories;

use App\Models\Pertanian;
use App\Models\Tanaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tanaman>
 */
class TanamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pertanian_id' => Pertanian::factory(),
            'nama_tanaman' => 'Cabai Rawit '.fake()->word(),
            'jenis' => fake()->randomElement(['Cabai Rawit', 'Cabai Keriting']),
            'jumlah' => fake()->numberBetween(10, 500),
            'tanggal_tanam' => fake()->date(),
            'status' => 'aktif',
        ];
    }
}
