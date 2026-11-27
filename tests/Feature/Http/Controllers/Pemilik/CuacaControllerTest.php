<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\Pertanian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CuacaControllerTest extends TestCase
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

    private function buatPertanianMilik(User $pemilik): Pertanian
    {
        return Pertanian::factory()->create([
            'pemilik_id' => $pemilik->id,
            'lokasi' => 'Bandung',
        ]);
    }

    public function test_redirects_guests_to_login(): void
    {
        $response = $this->get(route('pemilik.cuaca.index'));

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('unprivilegedRoles')]
    public function test_returns_403_for_unprivileged_roles(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get(route('pemilik.cuaca.index'));

        $response->assertForbidden();
    }

    public function test_displays_the_weather_forecast_from_openweathermap(): void
    {
        Cache::flush();
        config()->set('services.openweathermap.key', 'test-key');
        Http::preventStrayRequests();
        Http::fake([
            'api.openweathermap.org/data/2.5/forecast*' => Http::response([
                'list' => [
                    [
                        'dt_txt' => '2026-11-27 12:00:00',
                        'main' => ['temp' => 29.5, 'humidity' => 80],
                        'rain' => ['3h' => 1.2],
                    ],
                ],
            ]),
        ]);

        $pemilik = User::factory()->pemilik()->create();
        $pertanian = $this->buatPertanianMilik($pemilik);

        $response = $this->actingAs($pemilik)->get(
            route('pemilik.cuaca.index', ['pertanian_id' => $pertanian->id])
        );

        $response
            ->assertOk()
            ->assertSee('29.5')
            ->assertSee('80')
            ->assertSee('1.2');
        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://api.openweathermap.org/data/2.5/forecast?q=Bandung&appid=test-key&units=metric');
    }

    public function test_shows_an_error_when_the_weather_service_cannot_be_reached(): void
    {
        Cache::flush();
        config()->set('services.openweathermap.key', 'test-key');
        Http::preventStrayRequests();
        Http::fake([
            'api.openweathermap.org/data/2.5/forecast*' => Http::failedConnection(),
        ]);

        $pemilik = User::factory()->pemilik()->create();
        $this->buatPertanianMilik($pemilik);

        $response = $this->actingAs($pemilik)->get(route('pemilik.cuaca.index'));

        $response
            ->assertOk()
            ->assertSee('Informasi cuaca belum dapat ditampilkan.');
    }

    public function test_does_not_call_the_service_when_the_api_key_is_missing(): void
    {
        Cache::flush();
        config()->set('services.openweathermap.key', null);
        Http::preventStrayRequests();

        $pemilik = User::factory()->pemilik()->create();
        $this->buatPertanianMilik($pemilik);

        $response = $this->actingAs($pemilik)->get(route('pemilik.cuaca.index'));

        $response
            ->assertOk()
            ->assertSee('API key OpenWeatherMap belum dikonfigurasi.');
        Http::assertNothingSent();
    }

    public function test_returns_404_for_a_farm_owned_by_another_pemilik(): void
    {
        $pemilik = User::factory()->pemilik()->create();
        $pertanian = Pertanian::factory()->create();

        $response = $this->actingAs($pemilik)->get(
            route('pemilik.cuaca.index', ['pertanian_id' => $pertanian->id])
        );

        $response->assertNotFound();
    }
}
