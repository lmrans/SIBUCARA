<?php

namespace Tests\Feature\Http\Controllers\Pemilik;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_dashboard_for_pemilik(): void
    {
        $user = User::factory()->create(['role' => 'pemilik']);

        $response = $this->actingAs($user)->get(route('pemilik.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Dashboard Pemilik');
    }
}
