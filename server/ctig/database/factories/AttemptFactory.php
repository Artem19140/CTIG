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
            'student_id' => Student::inRandomOrder()->first()->id,
            'exam_id' => Exam::inRandomOrder()->first()->id,
            'total_mark' =>  fake()->numberBetween(5, 20),
            'started_at'=>now(),
            'organization_id' => Organization::inRandomOrder()->first()->id
        ];
    }

    public function status(AttemptStatus $status){
        return $this->state(function () use($status){
            return[
                'status'=> $status
            ];
        });
    }

    public function passed(){
        return $this->state(function (){
            return[
                'is_passed'=> true,
                'total_mark' => fake()->numberBetween(19, 22), 
            ];
        });
    }

    public function failed(){
        return $this->state(function (){
            return[
                'is_passed'=> false,
                'total_mark' => fake()->numberBetween(0, 10)
            ];
        });
    }

    public function today(){
        return $this->state(function (){
            return[
                'expired_at' => now()->addMinutes(80),
                'finished_at'=> now()->addMinutes(30),
                'started_at' => now()
            ];
        });
    }
}
