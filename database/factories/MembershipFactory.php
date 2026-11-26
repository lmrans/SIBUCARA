<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\Pertanian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Membership>
 */
class MembershipFactory extends Factory
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
            'user_id' => User::factory()->mandor(),
            'divalidasi_oleh' => null,
            'status' => 'menunggu',
            'tanggal_pengajuan' => fake()->date(),
            'tanggal_verifikasi' => null,
        ];
    }
}
