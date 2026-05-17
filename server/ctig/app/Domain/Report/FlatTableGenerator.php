<?php

namespace App\Domain\Report;

use App\Domain\Center\CenterContext;
use App\Enums\ReportType;
use App\Enums\TaskType;
use App\Events\ReportGenerated;
use App\Models\Attempt;
use Carbon\Carbon;

class FlatTableGenerator{
    public function execute(Carbon $dateFrom,Carbon $dateTo){
        $handle = fopen('php://output', 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $this->headers());
        $strNumber = 1;
        
        Attempt::query()
            ->forCenter(app(CenterContext::class)->id())
            ->with(['foreignNational', 'exam.type', 'answers' => [
                'taskVariant.task',
                'answer'
            ]])
            ->whereBetween('created_at', [
                $dateFrom->copy()->startOfDay(),
                $dateTo->copy()->endOfDay()
            ])

            ->whereNotNull('checked_at')

            ->chunkById(300, function($attempts) use($handle, &$strNumber) {
                foreach($attempts as $attempt){
                    $answers = $attempt->answers->sortBy(fn($a) => $a->taskVariant->task->order);
                    foreach($answers as $answer){    
                        $taskVariant = $answer->taskVariant;
                        $task = $taskVariant?->task;

                        $answerInTable = '';

                        if($task->type === TaskType::SingleChoice){
                            $answerInTable = $answer->answer['order'] ?? ''; 
                        }
                        if($task->type === TaskType::TextInput){
                            $answerInTable = $answer->answer; 
                        }
                            
                        fputcsv($handle, [
                            $strNumber,
                            $attempt->exam->type->level,
                            $attempt->foreign_national_id,
                            $attempt->id,
                            $taskVariant->task->order,
                            $taskVariant->fipi_number,
                            $answerInTable,
                            $answer->mark ?? 0,
                            $attempt->total_mark,
                            $this->isPassed($attempt)
                        ]);
                        $strNumber ++;
                    }
                }
            });   

        fclose($handle);
        event(new ReportGenerated(ReportType::FlatTable));
    }

    protected function headers(){
        return [
            'StrNumber',
            'Level',
            'ParticipantID',
            'HumanTestID',
            'TaskNumber',
            'TaskID',
            'Answer',
            'Mark',
            'TotalMark',
            'Resolution'
        ];
    }

    protected function isPassed(Attempt $attempt):string{
        if($attempt->isBanned()){
            return 'нет';
        }
        return $attempt->is_passed ? 'да' : 'нет';
    }
}