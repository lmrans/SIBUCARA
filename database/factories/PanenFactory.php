<?php

namespace Database\Factories;

use App\Models\Panen;
use App\Models\Tanaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Panen>
 */
class PanenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanaman_id' => Tanaman::factory(),
            'tanggal_panen' => fake()->date(),
            'jumlah' => fake()->randomFloat(2, 1, 500),
            'satuan' => 'kg',
            'kualitas' => fake()->randomElement(['A', 'B', 'C']),
        ];
    }
}
