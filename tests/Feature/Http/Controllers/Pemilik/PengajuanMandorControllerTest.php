<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Membership;
use App\Models\Pertanian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PengajuanMandorControllerTest extends TestCase
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

    public function test_lists_only_pending_applications_for_the_owned_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $pengajuan = Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => User::factory()->mandor()->create(['name' => 'Calon Mandor Sendiri']),
            'status' => 'menunggu',
        ]);
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'status' => 'disetujui',
        ]);
        Membership::factory()->create([
            'pertanian_id' => Pertanian::factory()->create()->id,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.pengajuan-mandor.index'));

        $response
            ->assertOk()
            ->assertSee('Calon Mandor Sendiri')
            ->assertViewHas('pengajuans', fn ($pengajuans): bool => $pengajuans->total() === 1
                && $pengajuans->first()->id === $pengajuan->id);
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.pengajuan-mandor.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.pengajuan-mandor.index'));

        $response->assertForbidden();
    }

    public function test_approves_an_application_and_records_the_validator(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $pengajuan = Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($pemilik)->put(
            route('pemilik.pengajuan-mandor.update', $pengajuan),
            ['status' => 'disetujui']
        );

        $response->assertRedirect(route('pemilik.pengajuan-mandor.index'));
        $this->assertDatabaseHas('memberships', [
            'id' => $pengajuan->id,
            'status' => 'disetujui',
            'divalidasi_oleh' => $pemilik->id,
        ]);
        $this->assertSame(
            now()->toDateString(),
            $pengajuan->fresh()->tanggal_verifikasi?->toDateString()
        );
    }

    public function test_rejects_an_application(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $pengajuan = Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($pemilik)->put(
            route('pemilik.pengajuan-mandor.update', $pengajuan),
            ['status' => 'ditolak']
        );

        $response->assertRedirect(route('pemilik.pengajuan-mandor.index'));
        $this->assertDatabaseHas('memberships', ['id' => $pengajuan->id, 'status' => 'ditolak']);
    }

    public function test_rejects_an_invalid_status(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $pengajuan = Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($pemilik)->put(
            route('pemilik.pengajuan-mandor.update', $pengajuan),
            ['status' => 'disetujui_dulu']
        );

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('memberships', ['id' => $pengajuan->id, 'status' => 'menunggu']);
    }

    public function test_returns_404_for_an_application_on_another_owners_farm(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pengajuan = Membership::factory()->create(['status' => 'menunggu']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.pengajuan-mandor.show', $pengajuan));

        $response->assertNotFound();
    }
}
