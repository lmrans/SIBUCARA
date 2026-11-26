<?php

namespace Database\Factories;

use App\Models\Pertanian;
use App\Models\Stok;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Stok>
 */
class StokFactory extends Factory
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
            'nama_barang' => fake()->words(2, true),
            'jenis_barang' => fake()->word(),
            'jumlah' => fake()->randomFloat(2, 1, 100),
            'satuan' => fake()->randomElement(['kg', 'liter', 'pcs']),
            'tanggal_masuk' => fake()->date(),
            'keterangan' => fake()->sentence(),
        ];
    }
}
