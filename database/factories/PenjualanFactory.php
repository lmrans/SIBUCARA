<?php

namespace Database\Factories;

use App\Models\Panen;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penjualan>
 */
class PenjualanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'panen_id' => Panen::factory(),
            'tanggal_penjualan' => fake()->date(),
            'pembeli' => fake()->name(),
            'jumlah' => 10,
            'harga_satuan' => 5000,
            'total_harga' => 50000,
        ];
    }
}
