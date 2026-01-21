<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamBlock;

class ExamBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'История',
            'Русский язык',
            'Право',
        ];

        foreach ($names as $name) {
            ExamBlock::create([
                'name' => $name,
                'exam_type_id' => fake()->numberBetween(1, 3),
                'creator_id' => fake()->numberBetween(1, 6),
                'min_mark' => fake()->numberBetween(3, 6),
            ]);
        }
    }
}
