<?php

namespace App\Domain\ExamDocument;

use App\Models\Attempt;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GenerateExamResultsAction{
    public function execute(Exam $exam){
        $exam->loadMissing([
                            'type' => [
                                'blocks' => fn(HasMany $q) => $q->select(['id', 'name', 'exam_type_id'])->orderBy('order'),  
                                'blocks.subblocks' => fn(HasMany $q) => $q->select(['id', 'name', 'block_id'])->orderBy('order'), 
                            ],
                            'enrollments' => [
                                                'attempt.answers.taskVariant.task:id,subblock_id,order',
                                                'foreignNational'
                                            ]
                        ]);    
        $headers = $this->getHeaders($exam);

        $rows = $this->getRows($exam);

        $pdf = Pdf::loadView('templates.pdf.exam.exam-results', [
            'exam' => $exam,
            'statementTable' => [
                'headers' => $headers,
                'rows' => $rows,
            ],
            'markTable' => [
                'rows' => $this->getRowsMarksTable($exam)
            ]
        ])->setPaper('a4', 'landscape');

         return $pdf;
    }

    protected function getHeaders(Exam $exam){
        return $exam->type->blocks->map(function($block){
            return [
                'id' => $block->id,
                'name'=>$block->name,
                'subblocks'=> $block->subblocks->map(function($subblock){
                    return [
                        'id' => $subblock->id,
                        'name' => $subblock->name
                    ];
                })
            ];
        });  
    }

    protected function getRows(Exam $exam){
        $subblocks = $exam->type->blocks
            ->sortBy('order')
            ->flatMap(fn ($b) => $b->subblocks->sortBy('order'));

        return $exam->enrollments->map(function($enrollment) use($subblocks){
            $answers = $enrollment->attempt?->answers ?? null;
            $isAbsent = $answers === null;

            $grouped = $isAbsent
                ? collect()
                : $answers->groupBy('taskVariant.task.subblock_id');
            $subblockMarks = $subblocks->map(function($subblock) use($grouped, $isAbsent){
                if($isAbsent){
                    return ['sum' => null];
                }
                return [
                    'sum' => $grouped[$subblock->id]?->sum('mark') ?? 0
                ];
            });
            return [
                'fullName' => $enrollment->foreignNational->full_name,
                'fullPassport' => $enrollment->foreignNational->full_passport,
                'startedAt' => $enrollment->attempt?->started_at?->format('H:i') ?? null,
                'finishedAt' => $enrollment->attempt?->finished_at?->format('H:i') ?? null,
                'result' => $this->getAttemptResultsStatus($enrollment->attempt),
                'subblockMarks' => $subblockMarks
            ];
        });
    }

    protected function getAttemptResultsStatus(Attempt | null $attempt){
        if(!$attempt){
            return 'н/я';
        }

        if($attempt->isBanned()){
            return 'Снят';
        }
        return $attempt->is_passed ? 'Сертификат' : 'Справка';
    }

    protected function getRowsMarksTable(Exam $exam){
        return $exam->enrollments->map(function($enrollment){
        
            return [
                'fullName' => $enrollment->foreignNational->full_name,
                'fullPassport' => $enrollment->foreignNational->full_passport,
                'answers' =>$enrollment->attempt?->answers->sortBy(function($answer){
                    return $answer->taskVariant->task->order;
                })
                ?? null
            ];
        });
    }   
}