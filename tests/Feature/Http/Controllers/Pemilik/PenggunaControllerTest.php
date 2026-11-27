<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Membership;
use App\Models\Pertanian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PenggunaControllerTest extends TestCase
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

    public function test_lists_only_the_owners_account_and_farm_members(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $mandor = User::factory()->mandor()->create(['name' => 'Mandor Bersama']);
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $mandor->id,
            'status' => 'disetujui',
        ]);
        $orangLain = User::factory()->pekerja()->create(['name' => 'Pekerja Asing']);

        $response = $this->actingAs($pemilik)->get(route('pemilik.pengguna.index'));

        $response
            ->assertOk()
            ->assertSee('Mandor Bersama')
            ->assertDontSee('Pekerja Asing');
    }

    public function test_does_not_show_a_member_with_a_pending_membership(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $calonMandor = User::factory()->mandor()->create(['name' => 'Calon Mandor Menunggu']);
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $calonMandor->id,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.pengguna.index'));

        $response
            ->assertOk()
            ->assertDontSee('Calon Mandor Menunggu');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.pengguna.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.pengguna.index'));

        $response->assertForbidden();
    }

    public function test_updates_a_farm_member_and_persists_the_change(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $mandor = User::factory()->mandor()->create();
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $mandor->id,
            'status' => 'disetujui',
        ]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.pengguna.update', $mandor), [
            'name' => 'Mandor Terbaru',
            'username' => 'mandor_terbaru',
            'email' => 'mandor.terbaru@example.com',
            'phone' => '081234567890',
            'role' => 'mandor',
        ]);

        $response->assertRedirect(route('pemilik.pengguna.index'));
        $this->assertDatabaseHas('users', [
            'id' => $mandor->id,
            'name' => 'Mandor Terbaru',
            'username' => 'mandor_terbaru',
            'role' => 'mandor',
        ]);
    }

    public function test_rejects_a_duplicate_email_without_changing_the_user(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $mandor = User::factory()->mandor()->create();
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $mandor->id,
            'status' => 'disetujui',
        ]);
        $emailDuplikat = User::factory()->pekerja()->create()->email;
        $namaAwal = $mandor->name;

        $response = $this->actingAs($pemilik)->put(route('pemilik.pengguna.update', $mandor), [
            'name' => 'Nama Baru',
            'username' => $mandor->username,
            'email' => $emailDuplikat,
            'phone' => null,
            'role' => 'mandor',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertSame($namaAwal, $mandor->fresh()->name);
    }

    public function test_returns_404_for_a_user_outside_the_owners_farms(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pemilikLain = User::factory()->pemilik()->create();
        $pertanianLain = Pertanian::factory()->create(['pemilik_id' => $pemilikLain->id]);
        $pekerja = User::factory()->pekerja()->create();
        Membership::factory()->create([
            'pertanian_id' => $pertanianLain->id,
            'user_id' => $pekerja->id,
        ]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.pengguna.show', $pekerja));

        $response->assertNotFound();
    }

    public function test_allows_the_owner_to_update_their_own_profile_without_changing_the_role(): void
    {
        $pemilik = User::factory()->pemilik()->create([
            'name' => 'Pemilik Awal',
            'username' => 'pemilik_awal',
        ]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.pengguna.update', $pemilik), [
            'name' => 'Pemilik Terbaru',
            'username' => 'pemilik_terbaru',
            'email' => $pemilik->email,
            'phone' => '081111111111',
            'role' => 'mandor',
        ]);

        $response->assertRedirect(route('pemilik.pengguna.index'));
        $this->assertDatabaseHas('users', [
            'id' => $pemilik->id,
            'name' => 'Pemilik Terbaru',
            'username' => 'pemilik_terbaru',
            'phone' => '081111111111',
            'role' => 'pemilik',
        ]);
    }

    public function test_hides_the_role_field_when_the_owner_edits_their_own_account(): void
    {
        $pemilik = User::factory()->pemilik()->create();

        $response = $this->actingAs($pemilik)->get(route('pemilik.pengguna.edit', $pemilik));

        $response
            ->assertOk()
            ->assertDontSee('name="role"', false);
    }

    public function test_prevents_the_owner_from_deleting_their_own_account(): void
    {
        $pemilik = User::factory()->pemilik()->create();

        $response = $this->actingAs($pemilik)->delete(route('pemilik.pengguna.destroy', $pemilik));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $pemilik->id]);
    }

    public function test_rejects_promoting_a_farm_member_to_owner(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $mandor = User::factory()->mandor()->create();
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $mandor->id,
            'status' => 'disetujui',
        ]);

        $response = $this->actingAs($pemilik)->put(route('pemilik.pengguna.update', $mandor), [
            'name' => $mandor->name,
            'username' => $mandor->username,
            'email' => $mandor->email,
            'phone' => $mandor->phone,
            'role' => 'pemilik',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertSame('mandor', $mandor->fresh()->role);
    }

    public function test_deletes_a_farm_member_after_confirmation_prompt_is_rendered(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create(['pemilik_id' => $pemilik->id]);
        $pekerja = User::factory()->pekerja()->create(['name' => 'Pekerja Akan Dihapus']);
        Membership::factory()->create([
            'pertanian_id' => $pertanian->id,
            'user_id' => $pekerja->id,
            'status' => 'disetujui',
        ]);

        $indexResponse = $this->actingAs($pemilik)->get(route('pemilik.pengguna.index'));
        $indexResponse
            ->assertOk()
            ->assertSee('return confirm', false);

        $response = $this->actingAs($pemilik)->delete(route('pemilik.pengguna.destroy', $pekerja));

        $response->assertRedirect(route('pemilik.pengguna.index'));
        $this->assertDatabaseMissing('users', ['id' => $pekerja->id]);
    }
}
