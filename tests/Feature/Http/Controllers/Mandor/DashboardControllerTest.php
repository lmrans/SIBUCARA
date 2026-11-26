<?php

namespace Tests\Feature\Http\Controllers\Mandor;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_dashboard_for_mandor(): void
    {
        $user = User::factory()->create(['role' => 'mandor']);

        $response = $this->actingAs($user)->get(route('mandor.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Dashboard Mandor');
    }
}
