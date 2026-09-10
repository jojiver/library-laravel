<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_token(): void
    {
        User::factory()->create([
            'email' => 'user@library.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@library.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_admin_can_login_and_receive_token(): void
    {
        User::factory()->create([
            'email' => 'admin@library.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@library.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@library.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@library.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'Invalid credentials.');
    }

    public function test_login_validates_input(): void
    {
        $response = $this->postJson('/api/admin/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}