<?php

namespace Database\Factories;

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
    $countries = ['UZ', 'TJ', 'AZ'];
    $country = fake()->randomElement($countries);

    $names = [
        'UZ' => [
            'male' => ['Азиз', 'Рустам', 'Жавлон', 'Бекзод', 'Улугбек'],
            'female' => ['Дилноза', 'Гулнора', 'Шахноза', 'Зилола', 'Малика'],
            'surnames' => ['Каримов', 'Абдуллаев', 'Турсунов', 'Расулов', 'Юсупов'],
        ],
        'TJ' => [
            'male' => ['Рустам', 'Джамшед', 'Фаррух', 'Далер', 'Шохрух'],
            'female' => ['Нилуфар', 'Фарзона', 'Заррина', 'Мехрибон', 'Шабнам'],
            'surnames' => ['Рахмонов', 'Исмоилов', 'Саидов', 'Холов', 'Мирзоев'],
        ],
        'AZ' => [
            'male' => ['Элвин', 'Орхан', 'Мурад', 'Турал', 'Анар'],
            'female' => ['Айсел', 'Лала', 'Нигяр', 'Гюнай', 'Севиндж'],
            'surnames' => ['Алиев', 'Мамедов', 'Гусейнов', 'Рзаев', 'Гулиев'],
        ],
    ];

    $gender = fake()->randomElement(['M', 'F']);

    $firstName = fake()->randomElement($names[$country][$gender === 'M' ? 'male' : 'female']);
    $lastName  = fake()->randomElement($names[$country]['surnames']);

    // отчество по региональному стилю (упрощённо)
    $patronymicBase = fake()->randomElement($names[$country]['male']);
    $patronymic = $patronymicBase . ($gender === 'M' ? 'ович' : 'овна');

    return [
        'surname' => $lastName,
        'name' => $firstName,
        'patronymic' => $patronymic,

        // normalized = lowercase кириллица
        'surname_normalized' => mb_strtolower($lastName),
        'name_normalized' => mb_strtolower($firstName),
        'patronymic_normalized' => mb_strtolower($patronymic),


        // latin — синхронизированная транслитерация
        'surname_latin' =>$this->translit($lastName),
        'name_latin' => $this->translit($firstName),
        'patronymic_latin' => $this->translit($patronymic),

        // паспорта (упрощённые, но региональные форматы)
        'passport_number' => match ($country) {
            'UZ' => fake()->numerify('AA#######'),
            'TJ' => fake()->numerify('AB#######'),
            'AZ' => fake()->numerify('C#######'),
        },

        'passport_series' => match ($country) {
            'UZ' => 'AB',
            'TJ' => 'TJ',
            'AZ' => 'AZ',
        },

        'issued_by' => 'МВД ' . fake()->numerify('####'),

        'issued_date' => fake()->dateTimeBetween('-10 years'),
        'passport_scan' => 'documents/passport.pdf',
        'passport_translate_scan' => 'documents/passport.pdf',

        'citizenship' => $country,

        'phone' => '79'.fake()->numerify('#########'),

        'creator_id' => User::factory(),

        'date_birth' => fake()->dateTimeBetween('-60 years', '-19 years'),

        'gender' => $gender,

        'address_reg' => fake()->streetAddress(),
    ];
}

private function translit(string $value): string
{
    $map = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e',
        'ж'=>'zh','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m',
        'н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u',
        'ф'=>'f','х'=>'h','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'shch',
        'ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',

        'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'E',
        'Ж'=>'Zh','З'=>'Z','И'=>'I','Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M',
        'Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U',
        'Ф'=>'F','Х'=>'H','Ц'=>'Ts','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Shch',
        'Ъ'=>'','Ы'=>'Y','Ь'=>'','Э'=>'E','Ю'=>'Yu','Я'=>'Ya',
    ];

    return strtr($value, $map);
}
}


