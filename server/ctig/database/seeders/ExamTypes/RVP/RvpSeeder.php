<?php

namespace Database\Seeders\ExamTypes\RVP;

use App\Models\Block;
use App\Models\ExamType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RvpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exam = ExamType::firstOrCreate(
            ['level' => 2],
            [
            'name' => "Разрешение на временное проживание в РФ",
            'short_name' => 'ВНЖ',
            'duration' => 90,
            'level' => 2,
            'certificate_name'=>'нету'
        ]);

        $russianBlock = Block::create([
            'name' => 'РУССКИЙ ЯЗЫК КАК ИНОСТРАННЫЙ',
            'exam_type_id' => $exam->id,
            'min_mark' => 6,
            'order' => 1
        ]);

        $historyBlock = Block::create([
            'name' => 'ИСТОРИЯ РОССИИ',
            'exam_type_id' => $exam->id, 
            'min_mark' => 6,
            'order' => 2
        ]);

        $legislationBlock = Block::create([
            'name' => 'ОСНОВЫ ЗАКОНОДАТЕЛЬСТВА РОССИЙСКОЙ ФЕДЕРАЦИИ',
            'exam_type_id' => $exam->id, 
            'min_mark' => 6,
            'order' => 3
        ]);
    }
}
