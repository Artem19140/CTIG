<?php

namespace Database\Factories;

use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'foreign_national_id' => ForeignNational::factory(),
            'center_id' => Center::factory(),
            'reg_number' => 260000,
            'creator_id' => User::factory()
        ];
    }

     public function hasPayment(){
        return $this->state(function(){
            return[
                'has_payment' => true,
            ];
        });
    }

    public function noPayment(){
        return $this->state(function(){
            return[
                'has_payment' => false  ,
            ];
        });
    }
}
