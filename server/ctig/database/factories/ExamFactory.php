<?php

namespace Database\Factories;

use App\Models\ExamAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ExamType;
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
            'begin_time' => fake()->dateTimeBetween('-30 days', '+30 days')->format('Y-m-d'),
            'exam_type_id' => ExamType::factory(),
            'creator_id' => User::factory(),
            'session_number'=>fake()->numberBetween(1, 101),
            'capacity'=>fake()->numberBetween(5, 20),
            'exam_address_id' => ExamAddress::factory(),
            'group'=> fake()->numberBetween(1, 4),
            'exam_date' => fake()->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d'),
        ];
    }

    public function withRandomCreator(): ExamFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
}
