<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskVariant>
 */
class TaskVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contain' => fake()->sentence(),
            'fipi_guid' => fake()->uuid(),
            'task_id' => Task::factory(),
            'is_actual'
        ];
    }
}
