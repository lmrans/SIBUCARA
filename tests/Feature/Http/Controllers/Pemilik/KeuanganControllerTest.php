<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Pertanian;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class KeuanganControllerTest extends TestCase
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

    /**
     * @return array<string, mixed>
     */
    private function dataTransaksi(Pertanian $pertanian): array
    {
        return [
            'pertanian_id' => $pertanian->id,
            'jenis' => 'pengeluaran',
            'kategori' => 'Pupuk',
            'jumlah' => 150000,
            'tanggal_transaksi' => '2026-11-14',
            'keterangan' => 'Pembelian pupuk organik',
        ];
    }

    public function test_lists_only_transactions_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        TransaksiKeuangan::factory()->create([
            'pertanian_id' => $pertanian->id,
            'kategori' => 'Pupuk Milik Sendiri',
        ]);
        TransaksiKeuangan::factory()->create(['kategori' => 'Pupuk Pemilik Lain']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.keuangan.index'));

        $response
            ->assertOk()
            ->assertSee('Pupuk Milik Sendiri')
            ->assertDontSee('Pupuk Pemilik Lain');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.keuangan.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.keuangan.index'));

        $response->assertForbidden();
    }

    public function test_creates_a_transaction_for_an_owned_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);

        $response = $this->actingAs($pemilik)->post(
            route('pemilik.keuangan.store'),
            $this->dataTransaksi($pertanian)
        );

        $response->assertRedirect(route('pemilik.keuangan.index'));
        $this->assertDatabaseHas('transaksi_keuangans', [
            'pertanian_id' => $pertanian->id,
            'user_id' => $pemilik->id,
            'kategori' => 'Pupuk',
            'jenis' => 'pengeluaran',
        ]);
    }

    public function test_rejects_a_transaction_for_a_farm_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create();

        $response = $this->actingAs($pemilik)->post(
            route('pemilik.keuangan.store'),
            $this->dataTransaksi($pertanian)
        );

        $response->assertSessionHasErrors('pertanian_id');
    }

    public function test_updates_an_owned_transaction_and_persists_the_change(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $transaksi = TransaksiKeuangan::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $pemilik->id,
        ]);

        $data = $this->dataTransaksi($pertanian);
        $data['kategori'] = 'Pupuk Diperbarui';

        $response = $this->actingAs($pemilik)->put(
            route('pemilik.keuangan.update', $transaksi),
            $data
        );

        $response->assertRedirect(route('pemilik.keuangan.index'));
        $this->assertDatabaseHas('transaksi_keuangans', [
            'id' => $transaksi->id,
            'kategori' => 'Pupuk Diperbarui',
        ]);
    }

    public function test_deletes_an_owned_transaction(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $transaksi = TransaksiKeuangan::factory()->create(['pertanian_id' => $pertanian->id]);

        $response = $this->actingAs($pemilik)->delete(route('pemilik.keuangan.destroy', $transaksi));

        $response->assertRedirect(route('pemilik.keuangan.index'));
        $this->assertDatabaseMissing('transaksi_keuangans', ['id' => $transaksi->id]);
    }

    public function test_returns_404_for_a_transaction_from_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $transaksi = TransaksiKeuangan::factory()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.keuangan.show', $transaksi));

        $response->assertNotFound();
    }
}
