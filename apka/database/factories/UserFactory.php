<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'location' => $this->faker->city,
            'linkedin_link' => $this->faker->url,
            'github_link' => $this->faker->url,
            'description' => $this->faker->text,
            'user_type' => $this->faker->randomElement([0, 1]),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
