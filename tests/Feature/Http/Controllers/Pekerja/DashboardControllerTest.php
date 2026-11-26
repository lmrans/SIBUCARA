<?php

namespace Tests\Feature\Http\Controllers\Pekerja;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_dashboard_for_pekerja(): void
    {
        $user = User::factory()->create(['role' => 'pekerja']);

        $response = $this->actingAs($user)->get(route('pekerja.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Dashboard Pekerja');
    }
}
