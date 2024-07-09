<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_panel_data()
    {
        $user = User::factory()->create(['user_type' => 1]);

        $response = $this->actingAs($user)->get('/panel');
        $response->assertStatus(200); 
    }


    public function test_update_panel_data()
    {
        $user = User::factory()->create(['user_type' => 1]);

        $response = $this->actingAs($user)->put('/panel', [
            'location' => 'Updated Location',
            'nip' => '1234567890',
        ]);

        $response->assertStatus(405);
    }

    public function test_add_job_post()
    {
        $user = User::factory()->create(['user_type' => 1]);

        $response = $this->actingAs($user)->post('/panel/job-posts', [
            'title' => 'Test Job',
            'description' => 'Test Description',
            'location' => 'Remote',
            'job_mode' => 'Remote',
            'foreign_language' => json_encode(['English']),
        ]);

        $response->assertStatus(200);
    }
}
