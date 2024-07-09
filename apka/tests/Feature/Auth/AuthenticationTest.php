<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'email@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'email@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);
    }

    public function test_users_can_logout()
    {
        $this->be($user = User::factory()->create([
            'password' => bcrypt('password'),
        ]));

        $response = $this->post('/logout');
        $response->assertRedirect('/');
    }
}
