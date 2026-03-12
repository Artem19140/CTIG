<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

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
    public function definition(): array
    {
        return [
            'address' => fake()->streetAddress,
            'max_capacity'=>fake()->numberBetween(8, 20),
            'organization_id' => Organization::inRandomOrder()->first()->id
        ];
    }
}
