<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForeignNational>
 */
class ForeignNationalFactory extends Factory
{

    public function withRandomCreator(): ForeignNationalFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
    public function definition(): array
    {   
        return [
            'surname'           => fake()->lastName(),
            'name'              => fake()->firstName(),
            'surname_normalized'           => fake()->lastName(),
            'name_normalized'              => fake()->firstName(),
            'patronymic'        => fake()->firstName() . 'ович', // или 'овна' для женских
            'surname_latin'     => fake()->lastName(),
            'name_latin'        => fake()->firstName(),
            'patronymic_latin'  => fake()->firstName() . 'ovich', // простая латинизация
            'passport_number' => fake()->unique()->numerify('#########'),
            'passport_series' => fake()->unique()->bothify('?????'),
            'issued_by' => 'МВД РФ №' . fake()->numerify('####'),
            'issued_date' => fake()->date('Y-m-d', '-10 years'),
            'citizenship' => fake()->randomElement(['UZ','KZ', 'AZ', 'US', 'UK']),
            'phone' => fake()->numerify('7##########'),
            'creator_id' => User::factory(),
            'date_birth' => fake()->dateTimeBetween('-60 years', Carbon::now()->subYears(19)->toDateString())->format('Y-m-d'),
            'gender' => fake()->randomElement(['M','F']),
            'address_reg' => fake()->streetAddress()
        ];

    }
}
