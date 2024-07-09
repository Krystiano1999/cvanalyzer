<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_confirmed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
