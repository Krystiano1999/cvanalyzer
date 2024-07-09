<?php

namespace Tests\Feature;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobApplicationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_job_application_without_cv()
    {
        $jobPost = JobPost::factory()->create([
            'required_skills' => ['PHP', 'Laravel'],
            'skills' => ['JavaScript', 'Vue.js'],
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        Http::fake([
            'http://localhost:8888/analyze' => Http::response([
                'entities' => [
                    'PHONE' => ['123456789'],
                    'HARD_SKILL' => ['PHP', 'Laravel'],
                    'SOFT_SKILL' => ['Communication', 'Teamwork'],
                    'LANGUAGES' => ['English', 'Polish']
                ]
            ], 200)
        ]);

        $this->partialMock(\App\Http\Controllers\JobApplicationController::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods(); 
            $mock->shouldReceive('analyzePdf')->andReturn([
                'entities' => [
                    'PHONE' => ['123456789'],
                    'HARD_SKILL' => ['PHP', 'Laravel'],
                    'SOFT_SKILL' => ['Communication', 'Teamwork'],
                    'LANGUAGES' => ['English', 'Polish']
                ]
            ]);
        });

        $response = $this->post('/applications', [
            'jobId' => $jobPost->id,
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('job_applications', [
            'job_post_id' => $jobPost->id,
            'email' => 'test@example.com',
            'name' => 'Test Name',
        ]);
    }
}
