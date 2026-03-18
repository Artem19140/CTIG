<?php

namespace Database\Seeders\ExamTypes\VNZH;

use App\Models\Block;
use App\Models\ExamType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VnzhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exam = ExamType::firstOrCreate(
            ['level' => 3],
            [
                'name' => "Вид на жительство",
                'short_name' => 'ВНЖ',
                'need_human_check' => true,
                'tasks_count' => 38,
                'min_mark' => 21,
                'duration' => 90,
                'level' => 3,
                'cost' => 5900,
                'has_speaking_tasks' => true,
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
