<?php

namespace Database\Factories;

use App\Enums\AttemptStatus;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Center;
use App\Models\ForeignNational;
use Carbon\Carbon;
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
            'foreign_national_id' => ForeignNational::factory(),
            'exam_id' =>Exam::factory(),
            'total_mark' =>  fake()->numberBetween(5, 20),
            'started_at'=>now(),
            'center_id' => Center::factory(),
            'enrollment_id' => Enrollment::factory()
        ];
    }

    public function acive(){
        return $this->state(function (){
            return[
                'status'=> AttemptStatus::Active,
                'last_activity_at' => Carbon::now()
            ];
        });
    }

    public function finished(){
        return $this->state(function (){
            return[
                'status'=> AttemptStatus::Finished,
                'finished_at' => Carbon::now(),
                'last_activity_at' => Carbon::now()
            ];
        });
    }

    public function expired(){
        return $this->state(function (){
            return[
               'expired_at' => now()->subMinutes(80),
            ];
        });
    }

    public function notExpired(){
        return $this->state(function (){
            return[
               'expired_at' => now()->addMinutes(80),
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

    public function banned(){
        return $this->state(function (){
            return[
                'status'=> AttemptStatus::Banned,
                'banned_at' => now()
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
