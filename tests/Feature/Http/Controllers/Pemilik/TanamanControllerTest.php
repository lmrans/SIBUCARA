<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TanamanControllerTest extends TestCase
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

    public function test_lists_only_plants_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        Tanaman::factory()->create([
            'pertanian_id' => $pertanian->id,
            'nama_tanaman' => 'Cabai Rawit Milik Sendiri',
        ]);
        Tanaman::factory()->create(['nama_tanaman' => 'Cabai Rawit Pemilik Lain']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.tanaman.index'));

        $response
            ->assertOk()
            ->assertSee('Cabai Rawit Milik Sendiri')
            ->assertDontSee('Cabai Rawit Pemilik Lain');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.tanaman.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.tanaman.index'));

        $response->assertForbidden();
    }

    public function test_creates_a_plant_in_an_owned_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);

        $response = $this->actingAs($pemilik)->post(route('pemilik.tanaman.store'), [
            'pertanian_id' => $pertanian->id,
            'nama_tanaman' => 'Cabai Rawit Baru',
            'jenis' => 'Cabai Rawit',
            'jumlah' => 25,
            'tanggal_tanam' => '2026-11-01',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('pemilik.tanaman.index'));
        $this->assertDatabaseHas('tanamen', [
            'pertanian_id' => $pertanian->id,
            'nama_tanaman' => 'Cabai Rawit Baru',
            'status' => 'aktif',
        ]);
    }

    public function test_rejects_a_plant_in_a_farm_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanianLain = Pertanian::factory()->create();

        $response = $this->actingAs($pemilik)->post(route('pemilik.tanaman.store'), [
            'pertanian_id' => $pertanianLain->id,
            'nama_tanaman' => 'Cabai Rawit Asing',
            'jenis' => 'Cabai Rawit',
            'jumlah' => 25,
            'tanggal_tanam' => '2026-11-01',
            'status' => 'aktif',
        ]);

        $response->assertSessionHasErrors('pertanian_id');
        $this->assertDatabaseMissing('tanamen', ['nama_tanaman' => 'Cabai Rawit Asing']);
    }

    public function test_updates_an_owned_plant_and_persists_the_change(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.tanaman.update', $tanaman), [
            'pertanian_id' => $pertanian->id,
            'nama_tanaman' => 'Cabai Rawit Diperbarui',
            'jenis' => 'Cabai Rawit',
            'jumlah' => 30,
            'tanggal_tanam' => '2026-11-02',
            'status' => 'nonaktif',
        ]);

        $response->assertRedirect(route('pemilik.tanaman.index'));
        $this->assertDatabaseHas('tanamen', [
            'id' => $tanaman->id,
            'nama_tanaman' => 'Cabai Rawit Diperbarui',
            'status' => 'nonaktif',
        ]);
    }

    public function test_deletes_an_owned_plant(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);

        $response = $this->actingAs($pemilik)->delete(route('pemilik.tanaman.destroy', $tanaman));

        $response->assertRedirect(route('pemilik.tanaman.index'));
        $this->assertDatabaseMissing('tanamen', ['id' => $tanaman->id]);
    }

    public function test_returns_404_for_a_plant_from_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $tanaman = Tanaman::factory()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.tanaman.show', $tanaman));

        $response->assertNotFound();
    }
}
