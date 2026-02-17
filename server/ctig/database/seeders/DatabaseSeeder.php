<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Student;

use Database\Seeders\ExamTypes\PATENT\PatentSeeder;
use Database\Seeders\ExamTypes\RVP\RvpSeeder;
use Database\Seeders\ExamTypes\VNZH\VnzhSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        
        User::factory(6)->create();
        
        $this->call([
            PatentSeeder::class,
            VnzhSeeder::class,
            RvpSeeder::class,
            ExamSeeder::class,
            ExamStudentSeeder::class
        ]);
        
        Student::factory(100) 
            ->sequence(fn ($sequence) => [
                'surname' => 'Фамилия ' . $sequence->index + 1,
                'name' => 'Имя ' . $sequence->index + 1,
                'patronymic' => 'Отчество' . $sequence->index + 1 ,
                'surname_latin' => 'Фамилия лат ' . $sequence->index + 1,
                'name_latin' => 'Имя лат ' . $sequence->index + 1,
                'patronymic_latin' => 'Отчество лат ' . $sequence->index + 1,
            ])
            ->withRandomCreator()
            ->create();
    }
}
