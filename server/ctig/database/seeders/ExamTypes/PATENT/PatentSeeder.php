<?php

namespace Database\Seeders\ExamTypes\PATENT;

use App\Enums\TaskTypeEnum;
use App\Models\Answer;
use App\Models\Block;
use App\Models\ExamType;
use App\Models\Subblock;
use App\Models\Task;
use App\Models\TaskVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatentSeeder extends Seeder
{
    public function run(): void
    {
        
        $exam = ExamType::firstOrCreate(
        ['level' => 1],
            [
            'name' => "Разрешение на работу (патент)",
            'short_name' => 'ПАТЕНТ',
            'duration' => 80,
            'certificate_name'=>'Сертификат о владении русским языком, знании истории России и основ законодательства Российской Федерации на уровне, соответствующем цели получения разрешения на работу или патента'
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
                                'task_variant_id' => $taskVariantCreated->id
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
                $this->historyBlock()
        ];
    }

    private function russianBlock(): array{
        return [
            'name' => 'РУССКИЙ ЯЗЫК КАК ИНОСТРАННЫЙ',
            'min_mark'=>6,
            'subblocks'=>[
                $this->audioSubblock(),
                $this->readingSubblock(),
                //$this->letterSubblock(),
                $this->vocabularAndGrammarSubblock()
            ]
        ];
    }

    private function audioSubblock(): array{
        $path = 'resources/data/PATENT/tasks/variants/';
        return [
            'name' => 'Аудирование',
            'min_mark' => 6,
            'tasks' => [
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task1.json')), true)
                ],
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task2.json')), true)
                ],
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task3.json')), true)
                ],
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task4.json')), true)
                ]
                
            ]
        ];
    }

    private function readingSubblock(): array{
        $path = 'resources/data/PATENT/tasks/variants/';
        return [
            'name' => 'Чтение', 
            'min_mark' => 6,
            'tasks' => [
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'description' => 'Прочитайте объявление и выберите правильный ответ.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task5.json')), true)
                ],
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'description' => 'Прочитайте текст и выберите правильный ответ.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task6.json')), true)
                ],
            ]
        ];
    }

    private function letterSubblock(): array{
        $path = 'resources/data/PATENT/tasks/variants/';
        return [
            'name' => 'Письмо',
            'min_mark' => 6,
            'tasks' => [
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'description' => 'Прочитайте объявление и выберите правильный ответ.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task7.json')), true)
                ],
            ]
        ];
    }

    private function vocabularAndGrammarSubblock(): array{
        $path = 'resources/data/PATENT/tasks/variants/';
        return [
            'name' => 'Лексика и грамматика',
            'min_mark' => 6,
            'tasks' => [
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'description' => 'Прочитайте объявление и выберите правильный ответ.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task8.json')), true)
                ],
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task9.json')), true)
                ],
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task10.json')), true)
                ],
                [
                    'type' => TaskTypeEnum::SingleChoice,
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task11.json')), true)
                ]
            ]
        ];
    }

    private function historyBlock(): array{
        $path = 'resources/data/PATENT/tasks/variants/';
        return [
            'name' => 'ИСТОРИЯ РОССИИ',
            'min_mark'=>6,
            'subblocks'=>[
                [
                    'name' => 'Тест',
                    'min_mark'=> 0,
                    'tasks' => [
                        [
                            'type' => TaskTypeEnum::SingleChoice,
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($path.'task12.json')), true)
                        ],
                        [
                            'type' => TaskTypeEnum::SingleChoice,
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($path.'task13.json')), true)
                        ],
                        [
                            'type' => TaskTypeEnum::SingleChoice,
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($path.'task14.json')), true)
                        ],
                        [
                            'type' => TaskTypeEnum::SingleChoice,
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($path.'task15.json')), true)
                        ],
                    ]
                ]
            ]
        ];
    }
}
