<?php

namespace Database\Seeders\ExamTypes\VNZH;

use App\Enums\TaskType;
use App\Models\Answer;
use App\Models\Block;
use App\Models\ExamType;
use App\Models\Subblock;
use App\Models\Task;
use App\Models\TaskVariant;
use Illuminate\Database\Seeder;

class VnzhSeeder extends Seeder
{
    protected string $path = 'resources/data/VNZH/';
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
            $this->historyBlock(),
            $this->legislationBlock()
        ];
    }

    private function russianBlock(): array{
        return [
            'name' => 'РУССКИЙ ЯЗЫК КАК ИНОСТРАННЫЙ',
            'min_mark'=>15,
            'subblocks'=>[
                $this->speakingSubblock(),
                $this->audioSubblock(),
                $this->readingSubblock(),
                $this->letterSubblock(),
                $this->vocabularAndGrammarSubblock()
            ]
        ];
    }

    private function speakingSubblock(){
        $path = $this->path;
        return [
            'name' => 'Говорение',
            'min_mark' => 4,
            'tasks' => [
                [
                    'type' => TaskType::Speaking,
                    'description' => 'Начните диалог.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task1.json')), true)
                ],
                [
                    'type' => TaskType::Speaking,
                    'description' => 'Примите участие в диалоге. Ответьте на вопросы собеседника полными предложениями.',
                    'mark' => 2,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task2.json')), true)
                ],
                [ 
                    'type' => TaskType::Speaking,
                    'description' => 'Начните диалог. Получите нужную Вам информацию. Будьте вежливы.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task3.json')), true)
                ],
                [ 
                    'type' => TaskType::Speaking,
                    'description' => 'Опишите картинку.',
                    'mark' => 2,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task4.json')), true)
                ]
            ]
        ];
    }

    private function audioSubblock(): array{
        $path = $this->path;
        return [
            'name' => 'Аудирование',
            'min_mark' => 4,
            'tasks' => [
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прослушайте диалог и определите, где происходит разговор. Выберите правильный ответ. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task5.json')), true)
                ],
                [
                    'type' => TaskType::TextInput,
                    'description' => 'Прослушайте диалог и дополните предложение в соответствии с информацией в тексте.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task6.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прослушайте начало диалога и выберите правильную ответную реплику. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task7.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прослушайте аудиозапись и выберите правильный ответ в заданиях 8-10. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task8.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прослушайте аудиозапись из заданиия 8. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($path.'task9.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прослушайте аудиозапись из заданиия 8. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task10.json')), true)
                ]
            ]
        ];
    }
    private function readingSubblock(): array{
        return [
            'name' => 'Чтение', 
            'min_mark' => 0,
            'tasks' => [
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прочитайте объявление. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task11.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прочитайте объявление. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task12.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прочитайте текст и выполните задания 13–17. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task13.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прочитайте текст из задания 13. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task14.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прочитайте текст из задания 13. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task15.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прочитайте текст из задания 13. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task16.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Прочитайте текст из задания 13. Укажите номер правильного ответа.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task17.json')), true)
                ],
            ]
        ];
    }

    private function letterSubblock(): array{
        return [
            'name' => 'Письмо',
            'min_mark' => 0,
            'tasks' => [
                [
                    'type' => TaskType::MultyInput,
                    'description' => 'Дайте развернутый ответ. Заполните анкету.',
                    'mark' => 2,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task18.json')), true)
                ],
                [
                    'type' => TaskType::Essay,
                    'description' => 'Напишите письмо.',
                    'mark' => 2,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task19.json')), true)
                ],
            ]
        ];
    }

    private function vocabularAndGrammarSubblock(): array{
        return [
            'name' => 'Лексика и грамматика',
            'min_mark' => 0,
            'tasks' => [
                [
                    'type' => TaskType::SingleChoice,
                    'description' => 'Выберите правильный ответ.',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task20.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => '',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task21.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => '',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task22.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => '',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task23.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => '',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task24.json')), true)
                ],
                [
                    'type' => TaskType::SingleChoice,
                    'description' => '',
                    'mark' => 1,
                    'variants'=> json_decode(file_get_contents(base_path($this->path.'task25.json')), true)
                ],
            ]
        ];
    }

    private function historyBlock(): array{
        return [
            'name' => 'ИСТОРИЯ РОССИИ',
            'min_mark'=>3,
            'subblocks'=>[
                [
                    'name' => '',
                    'min_mark'=> 0,
                    'tasks' => [
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task26.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task27.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task28.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task29.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task30.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task31.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task32.json')), true)
                        ],
                    ]
                ]
            ]
        ];
    }

    private function legislationBlock(){
        return [
            'name' => 'ОСНОВЫ ЗАКОНОДАТЕЛЬСТВА РОССИЙСКОЙ ФЕДЕРАЦИИ',
            'min_mark'=>3,
            'subblocks'=>[
                [
                    'name' => '',
                    'min_mark'=> 0,
                    'tasks' => [
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task33.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task34.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task35.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task36.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task37.json')), true)
                        ],
                        [
                            'type' => TaskType::SingleChoice,
                            'description' => '',
                            'mark' => 1,
                            'variants'=> json_decode(file_get_contents(base_path($this->path.'task38.json')), true)
                        ]
                    ]
                ]
            ]
        ];
    }
}
