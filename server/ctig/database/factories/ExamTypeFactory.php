<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamType>
 */
class ExamTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Внж',
            'short_name' => 'Внж',
            'level' => 1, 
            'certificate_name' => 'Имя сертификата',
            'duration' => 80,
            'cost' => fake()->numberBetween(3000, 5000),
            'tasks_count' => fake()->numberBetween(10, 30),
            'min_mark' => fake()->numberBetween(10, 30),
            'need_human_check' => fake()->numberBetween(0, 1),
            'has_speaking_tasks' => fake()->numberBetween(0, 1),
            'protocol_name' =>'sdfsdfsfsffssds'
        ];
    }

    public function notActive(){
        return $this->state(function(){
            return[
                'is_active'=>false
            ];
        });
    }
}
