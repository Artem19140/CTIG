<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{

    public function withRandomCreator(): StudentFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }

    public function namesLikeExam(): StudentFactory{
        return $this->state(function(int $examId){
            return[
                'surname' => 'Экзамен ' .$examId,
                'name' => 'Экзамен ' .$examId,
                'patronymic' => 'Экзамен' .$examId ,
                'surname_latin' => 'Экзамен ' .$examId,
                'name_latin' => 'Экзамен ' .$examId,
                'patronymic_latin' => 'Экзамен ' .$examId,
            ];
        });
    }
    public function definition(): array
    {   
        return [
            'surname'           => fake()->lastName(),
            'name'              => fake()->firstName(),
            'patronymic'        => fake()->firstName() . 'ович', // или 'овна' для женских
            'surname_latin'     => fake()->lastName(),
            'name_latin'        => fake()->firstName(),
            'patronymic_latin'  => fake()->firstName() . 'ovich', // простая латинизация
            'passport_number' => fake()->unique()->numerify('######'),
            'passport_series' => fake()->unique()->bothify('??'),
            'issued_by' => 'МВД РФ №' . fake()->numerify('####'),
            'issues_date' => fake()->date('Y-m-d', '-10 years'),
            'address_reg' => fake()->streetAddress,
            'migration_card_requisite' => fake()->bothify('MC#######'),
            'citizenship' => fake()->randomElement(['UZ','KZ', 'AZ', 'US', 'UK']),
            'phone' => fake()->numerify('+7##########'),
            'creator_id' => User::factory(),
            'date_birth' => fake()->dateTimeBetween('-60 years', Carbon::now()->subYears(19)->toDateString())->format('Y-m-d'),
        ];

    }
}
