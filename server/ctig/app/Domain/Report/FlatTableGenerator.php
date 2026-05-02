<?php

namespace App\Domain\Report;

use App\Enums\AttemptStatus;
use App\Enums\TaskType;
use App\Models\Attempt;
use Carbon\Carbon;

class FlatTableGenerator{
    public function execute(Carbon $dateFrom,Carbon $dateTo){
        $handle = fopen('php://output', 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $this->headers());
        $strNumber = 1;
        
        Attempt::with(['foreignNational', 'exam.type', 'answers' => [
            'taskVariant.task',
            'answer'
        ]])
            ->whereCreatedAtMore( $dateFrom->copy()->startOfDay())
            ->whereCreatedAtLess($dateTo = $dateTo->copy()->endOfDay())
            ->where('status', AttemptStatus::Checked)
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
                            $answer->mark,
                            $attempt->total_mark,
                            $this->isPassed($attempt)
                        ]);
                        $strNumber ++;
                    }
                }
            });   
        fclose($handle);
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