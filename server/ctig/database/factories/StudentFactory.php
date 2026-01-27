<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
        public function definition(): array
    {
        $namesByCountry = [
            'Узбекистан' => [
                'male' => ['Алишер','Даврон','Бахром'],
                'female' => ['Нилуфар','Самира','Гулина'],
                'surnames' => ['Абдуллаев','Икрамов','Каримова']
            ],
            'Таджикистан' => [
                'male' => ['Шахриёр','Рустам','Фарход'],
                'female' => ['Махина','Гулнора','Суҳроб'],
                'surnames' => ['Ҳасанов','Раҳимов','Сатторов']
            ],
            'Киргизстан' => [
                'male' => ['Азамат','Бек','Токтосун'],
                'female' => ['Айгүл','Жазира','Байжан'],
                'surnames' => ['Токтобаев','Султанов','Кадыров']
            ],
            'Беларусь' => [
                'male' => ['Иван','Александр','Дмитрий'],
                'female' => ['Мария','Елена','Ольга'],
                'surnames' => ['Иванов','Петров','Сидоров']
            ],
            'Азербайджан' => [
                'male' => ['Эмин','Рашад','Фарид'],
                'female' => ['Лейла','Нигяр','Севиндж'],
                'surnames' => ['Мамедов','Гулиев','Алиева']
            ],
            'Молдавия' => [
                'male' => ['Ион','Влад','Дан'],
                'female' => ['Анна','Елена','Мария'],
                'surnames' => ['Попеску','Ионеску','Джибулэ']
            ],
            'Казахстан' => [
                'male' => ['Нурлан','Алишер','Бахыт'],
                'female' => ['Айжан','Гульмира','Динара'],
                'surnames' => ['Касымов','Нургалиев','Абдуллаева']
            ],
        ];

        $faker = \Faker\Factory::create('ru_RU');

        $citizenship = $faker->randomElement(array_keys($namesByCountry));
        $gender = $faker->randomElement(['male', 'female']);

        $countryNames = $namesByCountry[$citizenship];

        $surname = $faker->randomElement($countryNames['surnames']);
        $name = $faker->randomElement($countryNames[$gender]);

        // Отчество: делаем простое русское (можно универсальное)
        $patronymic = $faker->firstNameMale . ($gender === 'male' ? 'ович' : 'овна');

        // Латиница через простой транслит
        $translit = function($str) {
            $converter = [
                'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'YO','Ж'=>'ZH','З'=>'Z','И'=>'I',
                'Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T',
                'У'=>'U','Ф'=>'F','Х'=>'KH','Ц'=>'TS','Ч'=>'CH','Ш'=>'SH','Щ'=>'SCH','Ъ'=>'','Ы'=>'Y','Ь'=>'',
                'Э'=>'E','Ю'=>'YU','Я'=>'YA',
                'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i',
                'й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t',
                'у'=>'u','ф'=>'f','х'=>'kh','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'',
                'э'=>'e','ю'=>'yu','я'=>'ya',
            ];
            return strtr($str, $converter);
        };

        $surname_latin = $translit($surname);
        $name_latin = $translit($name);
        $patronymic_latin = $patronymic ? $translit($patronymic) : null;


        return [
            'surname' => $surname,
            'name' => $name,
            'patronymic' => $patronymic,
            'date_birth' => $faker->date('Y-m-d', '-30 years'),
            'surname_latin' => $surname_latin,
            'name_latin' => $name_latin,
            'patronymic_latin' => $patronymic_latin,
            'passport_number' => $faker->unique()->numerify('######'),
            'passport_series' => $faker->unique()->bothify('??'),
            'issued_by' => 'МВД РФ №' . $faker->numerify('####'),
            'issues_date' => $faker->date('Y-m-d', '-10 years'),
            'address_reg' => $faker->streetAddress,
            'migration_card_requisite' => $faker->bothify('MC#######'),
            'citizenship' => $citizenship,
            'phone' => $faker->numerify('+7##########'),
        ];

    }
}
