<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Panen;
use App\Models\Penjualan;
use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PenjualanControllerTest extends TestCase
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
     * @param  array<string, mixed>  $overrides
     */
    private function buatPanenMilik(User $pemilik, array $overrides = []): Panen
    {
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create([
            'pertanian_id' => $pertanian->id,
            'nama_tanaman' => $overrides['nama_tanaman'] ?? 'Cabai Rawit',
        ]);

        return Panen::factory()->create(['tanaman_id' => $tanaman->id]);
    }

    public function test_lists_only_sales_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $panen = $this->buatPanenMilik($pemilik, ['nama_tanaman' => 'Cabai Rawit Sendiri']);
        Penjualan::factory()->create([
            'panen_id' => $panen->id,
            'pembeli' => 'Pembeli Sendiri',
        ]);
        Penjualan::factory()->create(['pembeli' => 'Pembeli Orang Lain']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.penjualan.index'));

        $response
            ->assertOk()
            ->assertSee('Pembeli Sendiri')
            ->assertDontSee('Pembeli Orang Lain');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.penjualan.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.penjualan.index'));

        $response->assertForbidden();
    }

    public function test_creates_a_sale_with_the_calculated_total_and_income_record(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $panen = $this->buatPanenMilik($pemilik);

        $response = $this->actingAs($pemilik)->post(route('pemilik.penjualan.store'), [
            'panen_id' => $panen->id,
            'tanggal_penjualan' => '2026-11-12',
            'pembeli' => 'Pedagang Pasar',
            'jumlah' => 12.5,
            'harga_satuan' => 4000,
        ]);

        $response->assertRedirect(route('pemilik.penjualan.index'));
        $this->assertDatabaseHas('penjualans', [
            'panen_id' => $panen->id,
            'pembeli' => 'Pedagang Pasar',
            'total_harga' => 50000,
        ]);
        $this->assertDatabaseHas('transaksi_keuangans', [
            'jenis' => 'pemasukan',
            'jumlah' => 50000,
            'user_id' => $pemilik->id,
        ]);
    }

    public function test_ignores_a_submitted_total_harga(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $panen = $this->buatPanenMilik($pemilik);

        $response = $this->actingAs($pemilik)->post(route('pemilik.penjualan.store'), [
            'panen_id' => $panen->id,
            'tanggal_penjualan' => '2026-11-12',
            'pembeli' => 'Pedagang Pasar',
            'jumlah' => 2,
            'harga_satuan' => 3000,
            'total_harga' => 999999,
        ]);

        $response->assertRedirect(route('pemilik.penjualan.index'));
        $this->assertDatabaseHas('penjualans', [
            'panen_id' => $panen->id,
            'total_harga' => 6000,
        ]);
    }

    public function test_rejects_a_harvest_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $panen = Panen::factory()->create();

        $response = $this->actingAs($pemilik)->post(route('pemilik.penjualan.store'), [
            'panen_id' => $panen->id,
            'tanggal_penjualan' => '2026-11-12',
            'pembeli' => 'Pedagang Asing',
            'jumlah' => 2,
            'harga_satuan' => 3000,
        ]);

        $response->assertSessionHasErrors('panen_id');
        $this->assertDatabaseMissing('penjualans', ['pembeli' => 'Pedagang Asing']);
    }

    public function test_updates_an_owned_sale_and_its_income_record(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $panen = $this->buatPanenMilik($pemilik);
        $penjualan = Penjualan::factory()->create([
            'panen_id' => $panen->id,
            'jumlah' => 2,
            'harga_satuan' => 3000,
            'total_harga' => 6000,
        ]);
        TransaksiKeuangan::factory()->create([
            'penjualan_id' => $penjualan->id,
            'pertanian_id' => $panen->tanaman->pertanian_id,
            'user_id' => $pemilik->id,
            'jenis' => 'pemasukan',
            'jumlah' => 6000,
        ]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.penjualan.update', $penjualan), [
            'panen_id' => $panen->id,
            'tanggal_penjualan' => '2026-11-13',
            'pembeli' => 'Pedagang Baru',
            'jumlah' => 3,
            'harga_satuan' => 3000,
        ]);

        $response->assertRedirect(route('pemilik.penjualan.index'));
        $this->assertDatabaseHas('penjualans', [
            'id' => $penjualan->id,
            'pembeli' => 'Pedagang Baru',
            'total_harga' => 9000,
        ]);
        $this->assertDatabaseHas('transaksi_keuangans', [
            'penjualan_id' => $penjualan->id,
            'jumlah' => 9000,
        ]);
    }

    public function test_deletes_an_owned_sale_and_its_income_record(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $panen = $this->buatPanenMilik($pemilik);
        $penjualan = Penjualan::factory()->create(['panen_id' => $panen->id]);
        TransaksiKeuangan::factory()->create([
            'penjualan_id' => $penjualan->id,
            'pertanian_id' => $panen->tanaman->pertanian_id,
            'user_id' => $pemilik->id,
            'jenis' => 'pemasukan',
        ]);

        $response = $this->actingAs($pemilik)->delete(route('pemilik.penjualan.destroy', $penjualan));

        $response->assertRedirect(route('pemilik.penjualan.index'));
        $this->assertDatabaseMissing('penjualans', ['id' => $penjualan->id]);
        $this->assertDatabaseMissing('transaksi_keuangans', ['penjualan_id' => $penjualan->id]);
    }

    public function test_returns_404_for_a_sale_from_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $penjualan = Penjualan::factory()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.penjualan.show', $penjualan));

        $response->assertNotFound();
    }
}
