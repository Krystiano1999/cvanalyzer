<?php

namespace Database\Factories;

use App\Models\Companies;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompaniesFactory extends Factory
{
    protected $model = Companies::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->text,
            'logo' => $this->faker->imageUrl,
            'location' => $this->faker->city,
            'nip' => $this->faker->unique()->numerify('##########'),
            'recruiter_id' => User::factory(),
        ];
    }
}
