<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\JadwalPekerjaan;
use App\Models\KegiatanBudidaya;
use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class JadwalControllerTest extends TestCase
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

    private function buatTugasMilik(User $pemilik, string $judul): Tugas
    {
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        $kegiatan = KegiatanBudidaya::factory()->create(['tanaman_id' => $tanaman->id]);

        return Tugas::factory()->create([
            'budidaya_id' => $kegiatan->id,
            'judul_tugas' => $judul,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function dataJadwal(Tugas $tugas): array
    {
        return [
            'tugas_id' => $tugas->id,
            'tanggal' => '2026-11-20',
            'waktu_mulai' => '07:30',
            'waktu_selesai' => '11:30',
            'keterangan' => 'Penyulaman pagi hari',
        ];
    }

    public function test_lists_only_schedules_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $tugas = $this->buatTugasMilik($pemilik, 'Tugas pertanian sendiri');
        JadwalPekerjaan::factory()->create(['tugas_id' => $tugas->id]);
        $tugasLain = Tugas::factory()->create(['judul_tugas' => 'Tugas pertanian orang lain']);
        JadwalPekerjaan::factory()->create(['tugas_id' => $tugasLain->id]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.jadwal.index'));

        $response
            ->assertOk()
            ->assertSee('Tugas pertanian sendiri')
            ->assertDontSee('Tugas pertanian orang lain');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.jadwal.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.jadwal.index'));

        $response->assertForbidden();
    }

    public function test_creates_a_schedule_for_an_owned_task(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $tugas = $this->buatTugasMilik($pemilik, 'Tugas milik sendiri');

        $response = $this->actingAs($pemilik)->post(
            route('pemilik.jadwal.store'),
            $this->dataJadwal($tugas)
        );

        $response->assertRedirect(route('pemilik.jadwal.index'));
        $this->assertDatabaseHas('jadwal_pekerjaans', [
            'tugas_id' => $tugas->id,
            'waktu_mulai' => '07:30:00',
            'waktu_selesai' => '11:30:00',
            'keterangan' => 'Penyulaman pagi hari',
        ]);
    }

    public function test_rejects_a_schedule_for_a_task_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $tugas = Tugas::factory()->create();

        $response = $this->actingAs($pemilik)->post(
            route('pemilik.jadwal.store'),
            $this->dataJadwal($tugas)
        );

        $response->assertSessionHasErrors('tugas_id');
    }

    public function test_reports_missing_required_schedule_fields(): void
    {
        $pemilik = User::factory()->pemilik()->create();

        $response = $this->actingAs($pemilik)->post(route('pemilik.jadwal.store'), []);

        $response->assertSessionHasErrors(['tugas_id', 'tanggal', 'waktu_mulai']);
    }

    public function test_updates_an_owned_schedule_and_persists_the_change(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $tugas = $this->buatTugasMilik($pemilik, 'Tugas milik sendiri');
        $jadwal = JadwalPekerjaan::factory()->create(['tugas_id' => $tugas->id]);

        $data = $this->dataJadwal($tugas);
        $data['waktu_mulai'] = '08:00';
        $data['keterangan'] = 'Jadwal diubah';

        $response = $this->actingAs($pemilik)->put(
            route('pemilik.jadwal.update', $jadwal),
            $data
        );

        $response->assertRedirect(route('pemilik.jadwal.index'));
        $this->assertDatabaseHas('jadwal_pekerjaans', [
            'id' => $jadwal->id,
            'waktu_mulai' => '08:00:00',
            'keterangan' => 'Jadwal diubah',
        ]);
    }

    public function test_returns_404_for_a_schedule_from_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $jadwal = JadwalPekerjaan::factory()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.jadwal.show', $jadwal));

        $response->assertNotFound();
    }
}
