<?php

namespace App\Actions\Reports;

use App\Enums\AttemptStatus;
use App\Enums\TaskType;
use App\Models\Attempt;
use Carbon\Carbon;

class GenerateFlatTableAction{
    public function execute(string $dateFrom,string $dateTo, $handle){
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);
        fputcsv($handle, $this->headers());
        $strNumber = 1;
        
        Attempt::with(['foreignNational', 'exam.examType', 'answers' => [
            'taskVariant.task',
            'answer'
        ]])
            ->where('started_at','>=',$dateFrom)
            ->where('started_at','<=', $dateTo)
            ->where('status', AttemptStatus::Checked)
            ->chunkById(1000, function($items) use($handle, &$strNumber) {
                foreach($items as $item){
                    $answers = $item->answers->sortBy(fn($a) => $a->taskVariant->task->order);
                    foreach($answers as $answer){    
                        $task = $answer->taskVariant?->task;
                        $answerInTable = '';

                        if($task->type === TaskType::SingleChoice){
                            $answerInTable = $answer->answer['order'] ?? ''; 
                        }
                        if($task->type === TaskType::TextInput){
                            $answerInTable = $answer->answer; 
                        }
                            
                        fputcsv($handle, [
                            $strNumber,
                            $item->exam->examType->level,
                            $item->foreign_national_id,
                            $item->id,
                            $answer->taskVariant->task->order,
                            $answer->taskVariant->fipi_number,
                            $answerInTable,
                            $answer->mark,
                            $item->total_mark,
                            $item->is_passed ? 'да' : 'нет'
                        ]);
                        $strNumber ++;
                    }
                }
            });   
        return true;
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
}