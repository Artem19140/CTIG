<?php

namespace Database\Factories;

use App\Models\Center;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    public function isActive(): AddressFactory{
        return $this->state(function(){
            return[
                'is_active' => true
            ];
        });
    }

    public function notActive(): AddressFactory{
        return $this->state(function(){
            return[
                'is_active' => false
            ];
        });
    }

    public function withCapacity(int $capacity): AddressFactory{
        return $this->state(function() use($capacity) {
            return[
                'max_capacity' => $capacity
            ];
        });
    }
    public function definition(): array
    {
        return [
            'address' => fake()->streetAddress,
            'max_capacity'=>fake()->numberBetween(8, 20),
            'center_id' => Center::inRandomOrder()->first()->id
        ];
    }
}
