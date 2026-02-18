<?php

namespace Database\Factories;

use App\Enums\AttemptStatusEnum;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attempt>
 */
class ExamAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'exam_id' => Exam::factory(),
            'total_mark' => fake()->numberBetween(5, 20)

        ];
    }

    public function banned(){
        return $this->state(function (){
            return[
                'status'=> AttemptStatusEnum::Banned
            ];
        });
    }

    public function passed(){
        return $this->state(function (){
            return[
                'is_passed'=> true
            ];
        });
    }

    public function failed(){
        return $this->state(function (){
            return[
                'is_passed'=> false
            ];
        });
    }
}
