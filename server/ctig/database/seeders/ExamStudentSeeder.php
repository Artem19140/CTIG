<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Student;


class ExamStudentSeeder extends Seeder
{

    public function run(): void
    {
        Exam::factory()
            ->has(Student::factory(20)->withRandomCreator()
            ->sequence(fn ($sequence) => [
                    'surname' => 'Экзамен ' . $sequence->index + 1,
                    'name' => 'Экзамен ' . $sequence->index + 1,
                    'patronymic' => 'Экзамен' . $sequence->index + 1 ,
                    'surname_latin' => 'Экзамен ' . $sequence->index + 1,
                    'name_latin' => 'Экзамен ' . $sequence->index + 1,
                    'patronymic_latin' => 'Экзамен ' . $sequence->index + 1,
                ])
            ->withRandomCreator())
            ->create();
    }
}
