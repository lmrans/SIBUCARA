<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'role' => 'mandor',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'test_user',
            'phone' => '081234567890',
            'role' => 'mandor',
        ]);
    }

    public function test_registration_requires_a_username(): void
    {
        $response = $this->post('/register', [
            'name' => 'Tanpa Username',
            'email' => 'tanpa.username@example.com',
            'phone' => '081234567890',
            'role' => 'mandor',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'tanpa.username@example.com']);
    }
}
