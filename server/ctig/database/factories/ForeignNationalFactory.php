<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;
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
    $countries = ['UZ', 'TJ', 'AZ'];
    $country = fake()->randomElement($countries);

    $names = [
        'UZ' => [
            'male' => ['Aziz', 'Rustam', 'Javlon', 'Bekzod', 'Ulugbek'],
            'female' => ['Dilnoza', 'Gulnora', 'Shahnoza', 'Zilola', 'Malika'],
            'surnames' => ['Karimov', 'Abdullayev', 'Tursunov', 'Rasulov', 'Yusupov'],
        ],
        'TJ' => [
            'male' => ['Rustam', 'Jamshed', 'Farrukh', 'Daler', 'Shohruh'],
            'female' => ['Nilufar', 'Farzona', 'Zarrina', 'Mehribon', 'Shabnam'],
            'surnames' => ['Rahmonov', 'Ismoilov', 'Saidov', 'Kholov', 'Mirzoev'],
        ],
        'AZ' => [
            'male' => ['Elvin', 'Orkhan', 'Murad', 'Tural', 'Anar'],
            'female' => ['Aysel', 'Lala', 'Nigar', 'Gunay', 'Sevinj'],
            'surnames' => ['Aliyev', 'Mammadov', 'Huseynov', 'Rzayev', 'Guliyev'],
        ],
    ];

    $gender = fake()->randomElement(['M', 'F']);

    $firstName = fake()->randomElement($names[$country][$gender === 'M' ? 'male' : 'female']);
    $lastName  = fake()->randomElement($names[$country]['surnames']);

    // отчество по региональному стилю (упрощённо)
    $patronymicBase = fake()->randomElement($names[$country]['male']);
    $patronymic = $patronymicBase . ($gender === 'M' ? 'ovich' : 'ovna');

    // упрощённая транслитерация (достаточно для тестов)
    $translit = fn($value) => Str::slug($value, '');

    return [
        'surname' => $lastName,
        'name' => $firstName,
        'patronymic' => $patronymic,

        // normalized = lowercase кириллица
        'surname_normalized' => mb_strtolower($lastName),
        'name_normalized' => mb_strtolower($firstName),

        // latin — синхронизированная транслитерация
        'surname_latin' => Str::ucfirst($translit($lastName)),
        'name_latin' => Str::ucfirst($translit($firstName)),
        'patronymic_latin' => Str::ucfirst($translit($patronymic)),

        // паспорта (упрощённые, но региональные форматы)
        'passport_number' => match ($country) {
            'UZ' => fake()->numerify('AA#######'),
            'TJ' => fake()->numerify('AB#######'),
            'AZ' => fake()->numerify('C#######'),
        },

        'passport_series' => match ($country) {
            'UZ' => 'UZ' . fake()->numerify('######'),
            'TJ' => 'TJ' . fake()->numerify('######'),
            'AZ' => 'AZ' . fake()->numerify('######'),
        },

        'issued_by' => match ($country) {
            'UZ' => 'IIV UZ №' . fake()->numerify('####'),
            'TJ' => 'ВКД РТ №' . fake()->numerify('####'),
            'AZ' => 'DİN AZ №' . fake()->numerify('####'),
        },

        'issued_date' => fake()->dateTimeBetween('-10 years'),

        'citizenship' => $country,

        'phone' => match ($country) {
            'UZ' => '998' . fake()->numerify('########'),
            'TJ' => '992' . fake()->numerify('#######'),
            'AZ' => '994' . fake()->numerify('#######'),
        },

        'creator_id' => User::factory(),

        'date_birth' => fake()->dateTimeBetween('-60 years', '-19 years'),

        'gender' => $gender,

        'address_reg' => fake()->streetAddress(),
    ];
}
}
