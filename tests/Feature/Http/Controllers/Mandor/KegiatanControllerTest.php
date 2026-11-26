<?php

namespace Tests\Feature\Http\Controllers\Mandor;

use App\Models\KegiatanBudidaya;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KegiatanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('mandor.kegiatan.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_forbids_user_who_is_not_mandor(): void
    {
        $user = User::factory()->create(['role' => 'pekerja']);

        $response = $this->actingAs($user)->get(route('mandor.kegiatan.index'));

        $response->assertForbidden();
    }

    public function test_lists_kegiatan_for_mandor(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);
        KegiatanBudidaya::factory()->create(['nama_kegiatan' => 'Menanam cabai']);

        $response = $this->actingAs($user)->get(route('mandor.kegiatan.index'));

        $response
            ->assertOk()
            ->assertSee('Menanam cabai');
    }

    public function test_renders_create_form_for_mandor(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);

        $response = $this->actingAs($user)->get(route('mandor.kegiatan.create'));

        $response
            ->assertOk()
            ->assertSee('Tambah Kegiatan Budidaya');
    }

    public function test_shows_kegiatan_details(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);
        $kegiatan = KegiatanBudidaya::factory()->create(['nama_kegiatan' => 'Detail kegiatan']);

        $response = $this->actingAs($user)->get(route('mandor.kegiatan.show', $kegiatan));

        $response
            ->assertOk()
            ->assertSee('Detail kegiatan');
    }

    public function test_creates_kegiatan_with_valid_data(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);

        $response = $this->actingAs($user)->post(route('mandor.kegiatan.store'), [
            'nama_kegiatan' => 'Pemupukan',
            'tanggal' => '2026-11-26',
            'deskripsi' => 'Pupuk organik',
            'status' => 'rencana',
        ]);

        $response->assertRedirect(route('mandor.kegiatan.index'));

        $this->assertDatabaseHas('kegiatan_budidayas', [
            'nama_kegiatan' => 'Pemupukan',
            'tanggal' => '2026-11-26 00:00:00',
            'status' => 'rencana',
        ]);
    }

    public function test_rejects_empty_payload(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);

        $response = $this->actingAs($user)->post(route('mandor.kegiatan.store'), []);

        $response->assertSessionHasErrors([
            'nama_kegiatan' => 'The nama kegiatan field is required.',
            'tanggal' => 'The tanggal field is required.',
            'status' => 'The status field is required.',
        ]);

        $this->assertDatabaseCount('kegiatan_budidayas', 0);
    }

    public function test_rejects_status_outside_allowed_values(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);

        $response = $this->actingAs($user)->post(route('mandor.kegiatan.store'), [
            'nama_kegiatan' => 'Penyiraman',
            'tanggal' => '2026-11-26',
            'status' => 'tidak-valid',
        ]);

        $response->assertSessionHasErrors([
            'status' => 'The selected status is invalid.',
        ]);
    }

    public function test_updates_kegiatan(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);
        $kegiatan = KegiatanBudidaya::factory()->create(['status' => 'rencana']);

        $response = $this->actingAs($user)->put(route('mandor.kegiatan.update', $kegiatan), [
            'nama_kegiatan' => 'Panen cabai',
            'tanggal' => '2026-12-01',
            'status' => 'selesai',
        ]);

        $response->assertRedirect(route('mandor.kegiatan.index'));

        $this->assertDatabaseHas('kegiatan_budidayas', [
            'id' => $kegiatan->id,
            'nama_kegiatan' => 'Panen cabai',
            'tanggal' => '2026-12-01 00:00:00',
            'status' => 'selesai',
        ]);
    }

    public function test_deletes_kegiatan(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);
        $kegiatan = KegiatanBudidaya::factory()->create();

        $response = $this->actingAs($user)->delete(route('mandor.kegiatan.destroy', $kegiatan));

        $response->assertRedirect(route('mandor.kegiatan.index'));

        $this->assertModelMissing($kegiatan);
    }
}
