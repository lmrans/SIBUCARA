<?php

namespace Database\Factories;

use App\Models\Pertanian;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransaksiKeuangan>
 */
class TransaksiKeuanganFactory extends Factory
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
            'user_id' => User::factory()->pemilik(),
            'penjualan_id' => null,
            'jenis' => fake()->randomElement(array_keys(TransaksiKeuangan::JENIS)),
            'kategori' => fake()->word(),
            'jumlah' => fake()->randomFloat(2, 1000, 100000),
            'tanggal_transaksi' => fake()->date(),
            'keterangan' => fake()->sentence(),
        ];
    }
}
