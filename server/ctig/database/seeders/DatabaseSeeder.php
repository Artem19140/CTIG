<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Exam;
use App\Models\Migrant;
use App\Models\ExamType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::factory(6)->create();

        ExamType::create([
            'name' => "Вид место жительства",
            'short_name' => 'ВНЖ',
            'duration' => 90,
            "creator_id" => 1,
        ]);
        ExamType::create([
            'name' => "Разрешение временного проживания",
            'short_name' => 'РВП',
            'duration' => 90,
            "creator_id" => 2,
        ]);
        ExamType::create([
            'name' => "ПАТЕНТ",
            'short_name' => 'ПАТЕНТ',
            'duration' => 80,
            "creator_id" => 3,
        ]);
        $this->call([
            ExamBlockSeeder::class,
        ]);
        Exam::factory(10)->create();
        Migrant::factory(100)->create();
    }
}
