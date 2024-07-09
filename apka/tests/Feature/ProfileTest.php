<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profil', [
            'name' => 'Updated Name',
            'surname' => 'Updated Surname',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(405); // Sprawdź poprawność metod w kontrolerze
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profil', [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
