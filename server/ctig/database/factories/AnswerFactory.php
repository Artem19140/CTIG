<?php

namespace Database\Factories;

use App\Models\ExamAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'contain' => fake()->sentence()
        ];
    }

    public function withRandomCreator(): AnswerFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
}
