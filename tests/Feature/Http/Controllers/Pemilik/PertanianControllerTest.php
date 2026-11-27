<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Membership;
use App\Models\Pertanian;
use App\Models\Tanaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PertanianControllerTest extends TestCase
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

    public function test_lists_only_the_owners_farms_and_their_summary(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create([
            'pemilik_id' => $pemilik->id,
            'nama_pertanian' => 'Kebun Milik Sendiri',
        ]);
        Pertanian::factory()->create([
            'nama_pertanian' => 'Kebun Pemilik Lain',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.pertanian.index'));

        $response
            ->assertOk()
            ->assertSee('Kebun Milik Sendiri')
            ->assertDontSee('Kebun Pemilik Lain')
            ->assertSee('1');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.pertanian.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.pertanian.index'));

        $response->assertForbidden();
    }

    public function test_creates_a_farm_owned_by_the_authenticated_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();

        $response = $this->actingAs($pemilik)->post(route('pemilik.pertanian.store'), [
            'nama_pertanian' => 'Kebun Baru',
            'lokasi' => 'Bandung',
            'luas_lahan' => 12.5,
            'deskripsi' => 'Lahan cabai rawit',
        ]);

        $response->assertRedirect(route('pemilik.pertanian.index'));
        $this->assertDatabaseHas('pertanians', [
            'pemilik_id' => $pemilik->id,
            'nama_pertanian' => 'Kebun Baru',
            'lokasi' => 'Bandung',
        ]);
    }

    public function test_rejects_an_empty_luas_lahan(): void
    {
        $pemilik = User::factory()->pemilik()->create();

        $response = $this->actingAs($pemilik)->post(route('pemilik.pertanian.store'), [
            'nama_pertanian' => 'Kebun Tanpa Luas',
            'lokasi' => 'Bandung',
            'luas_lahan' => '',
            'deskripsi' => null,
        ]);

        $response->assertSessionHasErrors('luas_lahan');
        $this->assertDatabaseMissing('pertanians', ['nama_pertanian' => 'Kebun Tanpa Luas']);
    }

    public function test_updates_an_owned_farm_and_persists_the_change(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.pertanian.update', $pertanian), [
            'nama_pertanian' => 'Kebun Diperbarui',
            'lokasi' => 'Garut',
            'luas_lahan' => 20,
            'deskripsi' => 'Deskripsi baru',
        ]);

        $response->assertRedirect(route('pemilik.pertanian.index'));
        $this->assertDatabaseHas('pertanians', [
            'id' => $pertanian->id,
            'nama_pertanian' => 'Kebun Diperbarui',
            'lokasi' => 'Garut',
        ]);
    }

    public function test_returns_404_for_a_farm_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanianLain = Pertanian::factory()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.pertanian.show', $pertanianLain));

        $response->assertNotFound();
    }

    public function test_deletes_an_empty_owned_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);

        $response = $this->actingAs($pemilik)->delete(route('pemilik.pertanian.destroy', $pertanian));

        $response->assertRedirect(route('pemilik.pertanian.index'));
        $this->assertDatabaseMissing('pertanians', ['id' => $pertanian->id]);
    }

    public function test_blocks_deleting_a_farm_that_still_has_related_data(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        Membership::factory()->create(['pertanian_id' => $pertanian->id]);
        Tanaman::factory()->create(['pertanian_id' => $pertanian->id]);

        $response = $this->actingAs($pemilik)->delete(route('pemilik.pertanian.destroy', $pertanian));

        $response
            ->assertRedirect(route('pemilik.pertanian.index'))
            ->assertSessionHas('error', fn (string $message): bool => str_contains($message, 'anggota')
                && str_contains($message, 'tanaman'));
        $this->assertDatabaseHas('pertanians', ['id' => $pertanian->id]);
    }

    public function test_returns_404_when_deleting_a_farm_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanianLain = Pertanian::factory()->create();

        $response = $this->actingAs($pemilik)->delete(route('pemilik.pertanian.destroy', $pertanianLain));

        $response->assertNotFound();
        $this->assertDatabaseHas('pertanians', ['id' => $pertanianLain->id]);
    }
}
