<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\KegiatanBudidaya;
use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MonitoringPekerjaanControllerTest extends TestCase
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

    private function buatTugas(User $pemilik, string $judul, string $status): Tugas
    {
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        $kegiatan = KegiatanBudidaya::factory()->create(['tanaman_id' => $tanaman->id]);

        return Tugas::factory()->create([
            'budidaya_id' => $kegiatan->id,
            'judul_tugas' => $judul,
            'status' => $status,
        ]);
    }

    public function test_counts_and_lists_only_tasks_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $this->buatTugas($pemilik, 'Tugas berjalan sendiri', 'berlangsung');
        $this->buatTugas($pemilik, 'Tugas selesai sendiri', 'selesai');
        $this->buatTugas(User::factory()->pemilik()->create(), 'Tugas orang lain', 'berlangsung');

        $response = $this->actingAs($pemilik)->get(route('pemilik.monitoring-pekerjaan.index'));

        $response
            ->assertOk()
            ->assertSee('Tugas berjalan sendiri')
            ->assertDontSee('Tugas orang lain')
            ->assertViewHas('ringkasan', fn (array $ringkasan): bool => $ringkasan['semua'] === 2
                && $ringkasan['berlangsung'] === 1
                && $ringkasan['selesai'] === 1);
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.monitoring-pekerjaan.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.monitoring-pekerjaan.index'));

        $response->assertForbidden();
    }
}
