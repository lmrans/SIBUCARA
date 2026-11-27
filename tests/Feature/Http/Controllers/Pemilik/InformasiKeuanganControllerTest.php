<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Pertanian;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class InformasiKeuanganControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array<int, string>>
     */
    public static function unprivilegedRoles(): array
    {
        return [
            'mandor' => ['mandor'],
            'pekerja' => ['pekerja'],
        ];
    }

    public function test_shows_only_the_owned_farms_financial_summary(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        TransaksiKeuangan::factory()->create([
            'pertanian_id' => $pertanian->id,
            'jenis' => 'pemasukan',
            'jumlah' => 200,
        ]);
        TransaksiKeuangan::factory()->create([
            'pertanian_id' => $pertanian->id,
            'jenis' => 'pengeluaran',
            'jumlah' => 50,
        ]);
        TransaksiKeuangan::factory()->create(['kategori' => 'Transaksi Pemilik Lain']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.informasi-keuangan.index'));

        $response
            ->assertOk()
            ->assertDontSee('Transaksi Pemilik Lain')
            ->assertViewHas('pemasukan', 200.0)
            ->assertViewHas('pengeluaran', 50.0)
            ->assertViewHas('saldo', 150.0);
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.informasi-keuangan.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.informasi-keuangan.index'));

        $response->assertForbidden();
    }
}
