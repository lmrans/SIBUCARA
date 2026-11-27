<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\KegiatanBudidaya;
use App\Models\Membership;
use App\Models\Notifikasi;
use App\Models\Pertanian;
use App\Models\Stok;
use App\Models\Tanaman;
use App\Models\TransaksiKeuangan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
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

    public function test_renders_dashboard_for_pemilik(): void
    {
        $user = User::factory()->pemilik()->create();

        $response = $this->actingAs($user)->get(route('pemilik.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Dashboard Pemilik');
    }

    public function test_renders_a_summary_scoped_to_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'status' => 'disetujui',
        ]);
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'status' => 'menunggu',
        ]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        $kegiatan = KegiatanBudidaya::factory()->create(['tanaman_id' => $tanaman->id]);
        Tugas::factory()->create([
            'budidaya_id' => $kegiatan->id,
            'status' => 'berlangsung',
        ]);
        Stok::factory()->create(['pertanian_id' => $pertanian->id]);
        Notifikasi::factory()->create([
            'user_id' => $pemilik->id,
            'status_baca' => 'belum_dibaca',
        ]);
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

        $pertanianLain = Pertanian::factory()->create();
        Membership::factory()->create([
            'pertanian_id' => $pertanianLain->id,
            'status' => 'disetujui',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.dashboard'));

        $response
            ->assertOk()
            ->assertViewHas('ringkasan', fn (array $ringkasan): bool => $ringkasan['total_pertanian'] === 1
                && $ringkasan['lahan_aktif'] === 1
                && $ringkasan['total_pekerja'] === 1
                && $ringkasan['total_tanaman'] === 1
                && $ringkasan['total_tugas'] === 1
                && $ringkasan['tugas_berlangsung'] === 1
                && $ringkasan['total_stok'] === 1
                && $ringkasan['notifikasi_belum_dibaca'] === 1
                && $ringkasan['saldo'] == 150);
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.dashboard'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.dashboard'));

        $response->assertForbidden();
    }
}
