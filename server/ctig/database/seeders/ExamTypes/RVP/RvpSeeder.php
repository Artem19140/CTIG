<?php

namespace Database\Seeders\ExamTypes\RVP;

use App\Enums\TaskTypeEnum;
use App\Models\Answer;
use App\Models\Block;
use App\Models\ExamType;
use App\Models\Subblock;
use App\Models\Task;
use App\Models\TaskVariant;
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
            'short_name' => 'РВП',
            'duration' => 90,
            'level' => 2,
            'certificate_name'=>'нету'
        ]);
        $orderTask = 1;
        $orderBlock = 1;
        foreach($this->examBlocks() as $block){
            $blockCreated = Block::create([
                'exam_type_id' => $exam->id,
                'min_mark' => $block['min_mark'],
                'name' => $block['name'],
                'order' => $orderBlock
            ]);
            $orderBlock += 1;
            $orderSubblock = 1;
            foreach($block['subblocks'] as $subblock){
                $subblockCreated = Subblock::create([
                    'block_id'=> $blockCreated->id,
                    'name' => $subblock['name'],
                    'min_mark' => $subblock['min_mark'],
                    'order' => $orderSubblock
                ]);
                $orderSubblock += 1;
                foreach($subblock['tasks'] as $task){
                    $taskCreated = Task::create([
                        'order' => $orderTask,
                        'subblock_id' => $subblockCreated->id,
                        'type' => $task['type'],
                        'mark' => $task['mark'],
                        'description' => $task['description'] ?? null,
                    ]);
                    foreach($task['variants'] as $variant){
                        $taskVariantCreated = TaskVariant::create([
                            'content' => $variant['content'],
                            'fipi_number' => $variant['fipi_number'],
                            'group_number' => $variant['group_number'] ?? null,
                            'task_id' => $taskCreated->id
                        ]);
                        $orderAnswer = 1;
                        foreach($variant['answers'] as $answer){
                            Answer::create([
                                'content' => $answer['content'],
                                'is_correct' => $answer['is_correct'],
                                'order' => $orderAnswer,
                                'task_variant_id' => $taskVariantCreated->id,
                                'file_path' => $answer['file_path'] ?? null
                            ]);
                            $orderAnswer +=1;
                        }
                    }
                    $orderTask+=1;
                }
            }
        }
        
    }
    public function examBlocks(){
        return [  
                $this->russianBlock(),
                // $this->historyBlock(),
                // $this->legislationBlock()
        ];
    }

    private function russianBlock(): array{
        return [
            'name' => 'РУССКИЙ ЯЗЫК КАК ИНОСТРАННЫЙ',
            'min_mark'=>6,
            'subblocks'=>[
                $this->speakingSubblock(),
                // $this->audioSubblock(),
                // $this->readingSubblock(),
                // $this->letterSubblock(),
                // $this->vocabularAndGrammarSubblock()
            ]
        ];
    }

    private function speakingSubblock(){
        $path = 'resources/data/RVP/';
        return [
            'name' => 'Говорение',
            'min_mark' => 6,
            'tasks' => [
                [
                    'type' => TaskTypeEnum::Speaking,
                    'description' => 'Начните диалог.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task1.json')), true)
                ],
            ]
        ];
    }

     private function audioSubblock(): array{
        return [
            'name' => 'Аудирование',
            'min_mark' => 6,
            'tasks' => [

            ]
        ];
    }

    private function readingSubblock(): array{
        return [
            'name' => 'Чтение', 
            'min_mark' => 6,
            'tasks' => [

            ]
        ];
    }

    private function letterSubblock(): array{
        return [
            'name' => 'Письмо',
            'min_mark' => 6,
            'tasks' => [

            ]
        ];
    }

    private function vocabularAndGrammarSubblock(): array{
        return [
            'name' => 'Лексика и грамматика',
            'min_mark' => 6,
            'tasks' => [

            ]
        ];
    }

    private function historyBlock(): array{
        return [
            'name' => 'ИСТОРИЯ РОССИИ',
            'min_mark'=>6,
            'subblocks'=>[
                [
                    'name' => 'Тест',
                    'min_mark'=> 0,
                    'tasks' => [

                    ]
                ]
            ]
        ];
    }

    private function legislationBlock(){
        return [
            'name' => 'ОСНОВЫ ЗАКОНОДАТЕЛЬСТВА РОССИЙСКОЙ ФЕДЕРАЦИИ',
            'min_mark'=>6,
            'subblocks'=>[
                [
                    'name' => 'Тест',
                    'min_mark'=> 0,
                    'tasks' => [

                    ]
                ]
            ]
        ];
    }
}
