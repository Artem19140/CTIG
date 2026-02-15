<?php

namespace Database\Factories;

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
            'creator_id' => User::factory()
        ];
    }

    public function withRandomCreator(): AddressFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
}
