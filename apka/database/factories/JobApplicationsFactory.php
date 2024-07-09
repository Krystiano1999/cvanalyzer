<?php

namespace Database\Factories;

use App\Models\JobApplications;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicationsFactory extends Factory
{
    protected $model = JobApplications::class;

    public function definition()
    {
        return [
            'job_post_id' => JobPost::factory(),
            'user_id' => User::factory(),
            'cv_path' => $this->faker->url,
            'user_points' => $this->faker->numberBetween(1, 100),
            'soft_skills' => json_encode($this->faker->words(3)),
            'hard_skills' => json_encode($this->faker->words(5)),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'languages' => json_encode($this->faker->words(2)),
        ];
    }
}
