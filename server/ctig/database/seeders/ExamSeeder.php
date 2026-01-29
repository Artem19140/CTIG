<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamType;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\ExamAddress;
use App\Models\User;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        ExamAddress::factory(3)->withRandomCreator()->create();

        ExamType::create([
            'name' => "Вид место жительства",
            'short_name' => 'ВНЖ',
            'duration' => 90,
            "creator_id" => User::inRandomOrder()->first()->id,
            'level' => fake()->numberBetween(1, 3),
        ]);
        ExamType::create([
            'name' => "Разрешение временного проживания",
            'short_name' => 'РВП',
            'duration' => 90,
            "creator_id" => User::inRandomOrder()->first()->id,
            'level' => fake()->numberBetween(1, 3),
        ]);
        ExamType::create([
            'name' => "ПАТЕНТ",
            'short_name' => 'ПАТЕНТ',
            'duration' => 80,
            "creator_id" => User::inRandomOrder()->first()->id,
            'level' => fake()->numberBetween(1, 3),
        ]);

        Exam::factory(10)->withRandomCreator()->create([
            'exam_address_id' => User::inRandomOrder()->first()->id
        ]);
        
        $names = [
            'История',
            'Русский язык',
            'Право',
        ];

        foreach ($names as $name) {
            ExamBlock::create([
                'name' => $name,
                'exam_type_id' => ExamType::inRandomOrder()->first()->id,
                'creator_id' => User::inRandomOrder()->first()->id,
                'min_mark' => fake()->numberBetween(3, 6),
                'order' => 1
            ]);
        }
    }
}
