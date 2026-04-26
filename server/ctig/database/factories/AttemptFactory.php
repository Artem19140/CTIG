<?php

namespace Database\Factories;

use App\Enums\AttemptStatus;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Center;
use App\Models\ForeignNational;
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
            'foreign_national_id' => ForeignNational::inRandomOrder()->first()->id,
            'exam_id' => Exam::inRandomOrder()->first()->id,
            'total_mark' =>  fake()->numberBetween(5, 20),
            'started_at'=>now(),
            'center_id' => Center::inRandomOrder()->first()->id,
            'enrollment_id' => Enrollment::factory()->create()
        ];
    }

    public function status(AttemptStatus $status){
        return $this->state(function () use($status){
            return[
                'status'=> $status
            ];
        });
    }

    public function checked(){
        return $this->state(function (){
            return[
                'status'=> AttemptStatus::Checked
            ];
        });
    }
    public function notChecked(){
        return $this->state(function (){
            return[
                'status'=> fake()->randomElement(AttemptStatus::unChecked())
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
