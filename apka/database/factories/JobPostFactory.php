<?php

namespace Database\Factories;

use App\Models\JobPost;
use App\Models\Companies;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    protected $model = JobPost::class;

    public function definition()
    {
        return [
            'recruiter_id' => User::factory(),
            'company_id' => Companies::factory(),
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->text,
            'salary_min' => $this->faker->numberBetween(3000, 5000),
            'salary_max' => $this->faker->numberBetween(5000, 10000),
            'required_skills' => json_encode($this->faker->words(3)),
            'skills' => json_encode($this->faker->words(5)),
            'employment_type' => json_encode(['full-time']),
            'experience' => json_encode(['junior']),
            'contract_type' => json_encode(['permanent']),
            'job_mode' => $this->faker->randomElement(['on-site', 'remote']),
            'foreign_language' => json_encode(['english']),
        ];
    }
}
