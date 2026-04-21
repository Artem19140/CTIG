<?php

namespace App\Domain\ExamDocument;

use App\Models\Attempt;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamResultsGenerator{
    public function execute(Exam $exam){
        $this->loadRelations($exam);

        return Pdf::loadView('templates.pdf.exam.exam-results', [
            'exam' => $exam,
            'statementTable' => [
                'headers' => $this->getHeadersStatement($exam),
                'rows' => $this->getRowsStatement($exam),
            ],
            'markTable' => [
                'rows' => $this->getRowsMarksTable($exam)
            ]
        ])->setPaper('a4', 'landscape');
    }

    public function loadRelations(Exam $exam){
        $exam->loadMissing([
            'type' => [
                'blocks' => fn(HasMany $q) => $q->orderBy('order'),  
                'blocks.subblocks' => fn(HasMany $q) => $q->orderBy('order'), 
            ],
            'enrollments' => [
                'attempt.answers.taskVariant.task',
                'foreignNational'
            ]
        ]);   
    }

    protected function getHeadersStatement(Exam $exam){
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

    protected function getRowsStatement(Exam $exam){
        $subblocks = $exam->type->blocks
            ->sortBy('order')
            ->flatMap(fn ($b) => $b->subblocks->sortBy('order'));

        return $exam->enrollments->map( function($enrollment) use( $subblocks ){
            $attempt = $enrollment->attempt;
            $answers = $attempt?->answers ?? collect();

            $answersBySubblock = $answers->groupBy(function ($a) {
                return $a->taskVariant?->task?->subblock_id;
            });
            $marksBySubblock = $subblocks->map(function($subblock) use($answersBySubblock){
                $sum = $answersBySubblock->get($subblock->id)?->sum('mark');
                return ['sum' =>  $sum];
            });
            return [
                'fullName' => $enrollment->foreignNational->full_name,
                'fullPassport' => $enrollment->foreignNational->full_passport,
                'startedAt' => $attempt?->started_at?->format('H:i') ?? null,
                'finishedAt' => $attempt?->finished_at?->format('H:i') ?? null,
                'result' => $this->getAttemptResultStatus($attempt),
                'subblockMarks' => $marksBySubblock
            ];
        });
    }

    protected function getAttemptResultStatus(Attempt | null $attempt){
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
                    return $answer->taskVariant?->task?->order ?? 0;
                }) 
            ];
        });
    }   
}