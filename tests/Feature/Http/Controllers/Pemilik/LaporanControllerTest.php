<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\BuktiPekerjaan;
use App\Models\KegiatanBudidaya;
use App\Models\LaporanPekerjaan;
use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LaporanControllerTest extends TestCase
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
    private function buatLaporan(array $overrides = []): LaporanPekerjaan
    {
        $pertanian = Pertanian::factory()->create($overrides['pertanian'] ?? []);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        $kegiatan = KegiatanBudidaya::factory()->create(['tanaman_id' => $tanaman->id]);
        $tugas = Tugas::factory()->create([
            'budidaya_id' => $kegiatan->id,
            'judul_tugas' => $overrides['judul'] ?? 'Tugas pekerjaan',
        ]);

        return LaporanPekerjaan::factory()->create([
            'tugas_id' => $tugas->id,
            'deskripsi' => $overrides['deskripsi'] ?? 'Laporan pekerjaan',
        ]);
    }

    public function test_lists_only_reports_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $this->buatLaporan([
            'pertanian' => ['pemilik_id' => $pemilik->id],
            'judul' => 'Tugas pertanian sendiri',
        ]);
        $this->buatLaporan(['judul' => 'Tugas pertanian orang lain']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan.index'));

        $response
            ->assertOk()
            ->assertSee('Tugas pertanian sendiri')
            ->assertDontSee('Tugas pertanian orang lain');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.laporan.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.laporan.index'));

        $response->assertForbidden();
    }

    public function test_shows_the_report_with_its_evidence(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $laporan = $this->buatLaporan([
            'pertanian' => ['pemilik_id' => $pemilik->id],
            'deskripsi' => 'Laporan dengan bukti',
        ]);
        BuktiPekerjaan::factory()->create([
            'laporan_id' => $laporan->id,
            'nama_file' => 'bukti_penyulaman.jpg',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan.show', $laporan));

        $response
            ->assertOk()
            ->assertSee('Laporan dengan bukti')
            ->assertSee('bukti_penyulaman.jpg');
    }

    public function test_returns_404_for_a_report_from_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $laporan = $this->buatLaporan();

        $response = $this->actingAs($pemilik)->get(route('pemilik.laporan.show', $laporan));

        $response->assertNotFound();
    }
}
