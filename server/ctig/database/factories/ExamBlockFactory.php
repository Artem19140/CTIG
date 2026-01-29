<?php

namespace Database\Factories;

use App\Models\ExamType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamBlock>
 */
class ExamBlockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'order'=>11,
            'exam_type_id' => ExamType::factory(),
            'name' => fake()->randomElement(
                [
                    'История',
                    'Русский язык',
                    'ОСНОВЫ ЗАКОНОДАТЕЛЬСТВА РОССИЙСКОЙ ФЕДЕРАЦИИ']),
            
        ];
    }

    public function withRandomCreator(): ExamBlockFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id,
            ];
        });
    }
}
