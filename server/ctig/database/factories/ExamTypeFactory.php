<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

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
            'name' => "ПАТЕНТ",
            'short_name' => 'ПАТЕНТ',
            'duration' => 80,
            "creator_id" => User::factory(),
            'level' => fake()->numberBetween(1, 3),
        ];
    }

    public function withRandomCreator(): ExamTypeFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }

    public function isNotActual(){
        return $this->state(function(){
            return[
                'is_actual'=>false
            ];
        });
    }
}
