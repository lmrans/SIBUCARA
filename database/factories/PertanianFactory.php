<?php

namespace Database\Factories;

use App\Models\Pertanian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pertanian>
 */
class PertanianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pemilik_id' => User::factory()->pemilik(),
            'nama_pertanian' => fake()->words(2, true).' Farm',
            'lokasi' => fake()->address(),
            'luas_lahan' => fake()->randomFloat(2, 1, 100),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
