<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'basic_salary' => $this->faker->numberBetween(1000, 10000),
            'department_id' => 1,
            'job_title' => $this->faker->jobTitle(),
            'hiring_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
