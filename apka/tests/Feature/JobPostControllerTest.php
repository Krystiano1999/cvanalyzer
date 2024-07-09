<?php

namespace Tests\Feature;

use App\Models\JobPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobPostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_job_posts()
    {
        $response = $this->post('/oferty/szukaj', [
            'query' => 'Developer',
        ]);

        $response->assertStatus(200);
    }
}
