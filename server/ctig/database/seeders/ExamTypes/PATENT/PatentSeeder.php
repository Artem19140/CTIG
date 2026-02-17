<?php

namespace Database\Seeders\ExamTypes\PATENT;

use App\Enums\TaskType;
use App\Enums\TaskTypeEnum;
use App\Models\Block;
use App\Models\ExamType;
use App\Models\Subblock;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function structure(){
        return [
            $this->russianBlock(),
            //$this->historyBlock(),
        ];
    }

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
    }

    private function russianBlock(): array{
        return [
            'name' => 'РУССКИЙ ЯЗЫК КАК ИНОСТРАННЫЙ',
                'short_name' => 'ПАТЕНТ',
                'duration' => 80,
                'certificate_name'=>'Сертификат о владении русским языком, знании истории России и основ законодательства Российской Федерации на уровне, соответствующем цели получения разрешения на работу или патента',
                'subblocks'=>[
                    $this->audioSubblock(),
                    $this->readingSubblock(),
                    $this->letterSubblock(),
                    $this->vocabularAndGrammarSubblock()
                ]
        ];
    }

    private function audioSubblock(): array{
        return [
            'name' => 'Аудирование',
            'min_mark' => 6,
            'tasks' => [
                $this->task1()
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
}
