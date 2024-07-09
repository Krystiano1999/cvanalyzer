<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_correct_password_must_be_provided_to_update_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'current_password' => 'wrong-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
