<?php

namespace Database\Factories;

use App\Models\BuktiPekerjaan;
use App\Models\LaporanPekerjaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BuktiPekerjaan>
 */
class BuktiPekerjaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'laporan_id' => LaporanPekerjaan::factory(),
            'nama_file' => fake()->word().'.jpg',
            'path_file' => 'bukti/'.fake()->uuid().'.jpg',
            'tipe_file' => 'image/jpeg',
        ];
    }
}
