<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Panen;
use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PanenControllerTest extends TestCase
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

    public function test_lists_only_harvests_from_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        Panen::factory()->create([
            'tanaman_id' => $tanaman->id,
            'kualitas' => 'A',
        ]);
        Panen::factory()->create(['kualitas' => 'B']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.panen.index'));

        $response
            ->assertOk()
            ->assertViewHas('panens', fn ($panens): bool => $panens->total() === 1
                && $panens->first()->kualitas === 'A');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.panen.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.panen.index'));

        $response->assertForbidden();
    }

    public function test_creates_a_harvest_for_a_plant_in_an_owned_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);

        $response = $this->actingAs($pemilik)->post(route('pemilik.panen.store'), [
            'tanaman_id' => $tanaman->id,
            'tanggal_panen' => '2026-11-10',
            'jumlah' => 25.5,
            'satuan' => 'kg',
            'kualitas' => 'A',
        ]);

        $response->assertRedirect(route('pemilik.panen.index'));
        $this->assertDatabaseHas('panens', [
            'tanaman_id' => $tanaman->id,
            'kualitas' => 'A',
        ]);
        $this->assertSame(
            '2026-11-10',
            Panen::firstWhere('kualitas', 'A')?->tanggal_panen?->toDateString()
        );
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $pemilik->id,
            'jenis' => 'panen',
            'status_baca' => 'belum_dibaca',
        ]);
    }

    public function test_rejects_a_harvest_for_a_plant_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $tanaman = Tanaman::factory()->create();

        $response = $this->actingAs($pemilik)->post(route('pemilik.panen.store'), [
            'tanaman_id' => $tanaman->id,
            'tanggal_panen' => '2026-11-10',
            'jumlah' => 25.5,
            'satuan' => 'kg',
            'kualitas' => 'A',
        ]);

        $response->assertSessionHasErrors('tanaman_id');
    }

    public function test_updates_an_owned_harvest_and_persists_the_change(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        $panen = Panen::factory()->create(['tanaman_id' => $tanaman->id]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.panen.update', $panen), [
            'tanaman_id' => $tanaman->id,
            'tanggal_panen' => '2026-11-11',
            'jumlah' => 30,
            'satuan' => 'kg',
            'kualitas' => 'B',
        ]);

        $response->assertRedirect(route('pemilik.panen.index'));
        $this->assertDatabaseHas('panens', [
            'id' => $panen->id,
            'kualitas' => 'B',
        ]);
    }

    public function test_deletes_an_owned_harvest_without_a_sale(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $tanaman = Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);
        $panen = Panen::factory()->create(['tanaman_id' => $tanaman->id]);

        $response = $this->actingAs($pemilik)->delete(route('pemilik.panen.destroy', $panen));

        $response->assertRedirect(route('pemilik.panen.index'));
        $this->assertDatabaseMissing('panens', ['id' => $panen->id]);
    }

    public function test_returns_404_for_a_harvest_from_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $panen = Panen::factory()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.panen.show', $panen));

        $response->assertNotFound();
    }
}
