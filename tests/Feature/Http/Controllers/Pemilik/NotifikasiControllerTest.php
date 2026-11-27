<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class NotifikasiControllerTest extends TestCase
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

    public function test_lists_only_notifications_for_the_signed_in_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        Notifikasi::factory()->create([
            'user_id' => $pemilik->id,
            'judul' => 'Notifikasi sendiri',
        ]);
        Notifikasi::factory()->create([
            'judul' => 'Notifikasi pengguna lain',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pemilik.notifikasi.index'));

        $response
            ->assertOk()
            ->assertSee('Notifikasi sendiri')
            ->assertDontSee('Notifikasi pengguna lain');
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.notifikasi.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.notifikasi.index'));

        $response->assertForbidden();
    }

    public function test_marks_one_notification_as_read(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $notifikasi = Notifikasi::factory()->create([
            'user_id' => $pemilik->id,
            'status_baca' => 'belum_dibaca',
        ]);

        $response = $this->actingAs($pemilik)->put(
            route('pemilik.notifikasi.update', $notifikasi)
        );

        $response->assertRedirect(route('pemilik.notifikasi.index'));
        $this->assertDatabaseHas('notifikasis', [
            'id' => $notifikasi->id,
            'status_baca' => 'dibaca',
        ]);
    }

    public function test_marks_all_own_notifications_as_read(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        Notifikasi::factory()->count(2)->create([
            'user_id' => $pemilik->id,
            'status_baca' => 'belum_dibaca',
        ]);
        $notifikasiLain = Notifikasi::factory()->create([
            'status_baca' => 'belum_dibaca',
        ]);

        $response = $this->actingAs($pemilik)->post(
            route('pemilik.notifikasi.tandai-semua-dibaca')
        );

        $response->assertRedirect(route('pemilik.notifikasi.index'));
        $this->assertSame(0, Notifikasi::where('user_id', $pemilik->id)
            ->where('status_baca', 'belum_dibaca')
            ->count());
        $this->assertDatabaseHas('notifikasis', [
            'id' => $notifikasiLain->id,
            'status_baca' => 'belum_dibaca',
        ]);
    }

    public function test_returns_404_for_a_notification_owned_by_another_user(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $notifikasi = Notifikasi::factory()->create();

        $response = $this->actingAs($pemilik)->put(
            route('pemilik.notifikasi.update', $notifikasi)
        );

        $response->assertNotFound();
    }
}
