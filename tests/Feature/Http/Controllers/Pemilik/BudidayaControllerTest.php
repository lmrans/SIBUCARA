<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\KegiatanBudidaya;
use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BudidayaControllerTest extends TestCase
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

    public function test_lists_only_cultivation_activities_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        KegiatanBudidaya::factory()->create([
            'tanaman_id' => $tanaman->id,
            'nama_kegiatan' => 'Kegiatan Milik Sendiri',
        ]);
        KegiatanBudidaya::factory()->create(['nama_kegiatan' => 'Kegiatan Pemilik Lain']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.budidaya.index'));

        $response
            ->assertOk()
            ->assertSee('Kegiatan Milik Sendiri')
            ->assertDontSee('Kegiatan Pemilik Lain');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.budidaya.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.budidaya.index'));

        $response->assertForbidden();
    }

    public function test_creates_an_activity_for_a_plant_in_an_owned_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);

        $response = $this->actingAs($pemilik)->post(route('pemilik.budidaya.store'), [
            'tanaman_id' => $tanaman->id,
            'nama_kegiatan' => 'Penyulaman',
            'tanggal' => '2026-11-03',
            'deskripsi' => 'Menyulam tanaman yang mati',
            'status' => 'rencana',
        ]);

        $response->assertRedirect(route('pemilik.budidaya.index'));
        $this->assertDatabaseHas('kegiatan_budidayas', [
            'tanaman_id' => $tanaman->id,
            'nama_kegiatan' => 'Penyulaman',
            'status' => 'rencana',
        ]);
    }

    public function test_rejects_an_activity_for_a_plant_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $tanamanLain = Tanaman::factory()->create();

        $response = $this->actingAs($pemilik)->post(route('pemilik.budidaya.store'), [
            'tanaman_id' => $tanamanLain->id,
            'nama_kegiatan' => 'Penyulaman Asing',
            'tanggal' => '2026-11-03',
            'deskripsi' => null,
            'status' => 'rencana',
        ]);

        $response->assertSessionHasErrors('tanaman_id');
        $this->assertDatabaseMissing('kegiatan_budidayas', ['nama_kegiatan' => 'Penyulaman Asing']);
    }

    public function test_updates_an_owned_activity_and_persists_the_change(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        $kegiatan = KegiatanBudidaya::factory()->create(['tanaman_id' => $tanaman->id]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.budidaya.update', $kegiatan), [
            'tanaman_id' => $tanaman->id,
            'nama_kegiatan' => 'Pemupukan',
            'tanggal' => '2026-11-04',
            'deskripsi' => 'Pupuk organik',
            'status' => 'berlangsung',
        ]);

        $response->assertRedirect(route('pemilik.budidaya.index'));
        $this->assertDatabaseHas('kegiatan_budidayas', [
            'id' => $kegiatan->id,
            'nama_kegiatan' => 'Pemupukan',
            'status' => 'berlangsung',
        ]);
    }

    public function test_returns_404_for_an_activity_from_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $kegiatan = KegiatanBudidaya::factory()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.budidaya.show', $kegiatan));

        $response->assertNotFound();
    }
}
