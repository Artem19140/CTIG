<?php

namespace Database\Factories;

use App\Enums\AttemptStatus;
use App\Models\Exam;
use App\Models\Organization;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attempt>
 */
class AttemptFactory extends Factory
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
            'total_mark' => null, //fake()->numberBetween(5, 20)
            'started_at'=>now(),
            'organization_id' => Organization::inRandomOrder()->first()->id
        ];
    }

    public function banned(){
        return $this->state(function (){
            return[
                'status'=> AttemptStatus::Banned
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
