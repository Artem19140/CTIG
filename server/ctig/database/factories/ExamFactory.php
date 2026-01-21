<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_date' => fake()->date(),
            'exam_type_id' => fake()->numberBetween(1, 3),
            'creator_id'=>fake()->numberBetween(1, 6),
            'session_number'=>fake()->numberBetween(1, 101)
        ];
    }
}
