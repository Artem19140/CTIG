<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAddress>
 */
class ExamAddressFactory extends Factory
{
    public function isActual(): ExamAddressFactory{
        return $this->state(function(){
            return[
                'is_actual' => 1
            ];
        });
    }

    public function nooActual(): ExamAddressFactory{
        return $this->state(function(){
            return[
                'is_actual' => 0
            ];
        });
    }
    public function definition(): array
    {
        return [
            'address' => fake()->streetAddress,
            'creator_id' => User::factory(),
            'is_actual' => 1
        ];
    }

    public function withRandomCreator(): ExamAddressFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
}
