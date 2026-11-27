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

class LaporanPertanianControllerTest extends TestCase
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
    private function buatData(User $pemilik, string $pembeli, string $tanggal): array
    {
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create([
            'pertanian_id' => $pertanian->id,
            'nama_tanaman' => 'Cabai Rawit '.$pembeli,
        ]);
        $panen = Panen::factory()->create([
            'tanaman_id' => $tanaman->id,
            'tanggal_panen' => $tanggal,
        ]);
        $penjualan = Penjualan::factory()->create([
            'panen_id' => $panen->id,
            'tanggal_penjualan' => $tanggal,
            'pembeli' => $pembeli,
        ]);
        TransaksiKeuangan::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $pemilik->id,
            'jenis' => 'pemasukan',
            'jumlah' => 100,
            'tanggal_transaksi' => $tanggal,
            'keterangan' => 'Transaksi '.$pembeli,
        ]);

        return compact('panen', 'penjualan');
    }

    public function test_shows_only_the_owned_farms_report_data(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $this->buatData($pemilik, 'Pembeli Sendiri', '2026-11-10');
        $this->buatData(User::factory()->pemilik()->create(), 'Pembeli Orang Lain', '2026-11-11');

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan-pertanian.index'));

        $response
            ->assertOk()
            ->assertSee('Pembeli Sendiri')
            ->assertDontSee('Pembeli Orang Lain');
    }

    public function test_filters_the_report_by_the_requested_period(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $this->buatData($pemilik, 'Dalam Periode', '2026-11-10');
        $this->buatData($pemilik, 'Luar Periode', '2026-10-10');

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan-pertanian.index', [
            'dari' => '2026-11-01',
            'sampai' => '2026-11-30',
        ]));

        $response
            ->assertOk()
            ->assertViewHas('panens', fn ($panens): bool => $panens->count() === 1)
            ->assertViewHas('penjualans', fn ($penjualans): bool => $penjualans->count() === 1)
            ->assertViewHas('transaksis', fn ($transaksis): bool => $transaksis->count() === 1);
    }

    public function test_rejects_a_period_that_ends_before_it_starts(): void
    {
        $pemilik = User::factory()->pemilik()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan-pertanian.index', [
            'dari' => '2026-11-20',
            'sampai' => '2026-11-10',
        ]));

        $response->assertSessionHasErrors('sampai');
    }

    public function test_generates_a_pdf_report(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $this->buatData($pemilik, 'Pembeli PDF', '2026-11-10');

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan-pertanian.pdf'));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_generates_an_excel_report(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $this->buatData($pemilik, 'Pembeli Excel', '2026-11-10');

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan-pertanian.excel'));

        $response->assertOk();
        $this->assertStringContainsString(
            '.xlsx',
            (string) $response->headers->get('Content-Disposition')
        );
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.laporan-pertanian.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.laporan-pertanian.index'));

        $response->assertForbidden();
    }
}
