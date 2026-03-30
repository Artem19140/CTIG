<?php

namespace App\Actions\Exam\Enrollment;

use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;
use App\Actions\Exam\Validation\EnsureExamIsNotCompletedAction;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;

class ChangePaymentStatusAction{
    public function __construct(
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled,
        protected EnsureExamIsNotCompletedAction $ensureExamIsNotCompleted
    ){}

    public function execute(Exam  $exam, ForeignNational $foreignNational){
        $this->ensureExamIsNotCancelled->execute($exam);
        $this->ensureExamIsNotCompleted->execute($exam);
        
        $enrollment = $exam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->first();
        
        if(!$enrollment){
            throw new BusinessException('Записи на экзамен не существует');
        }
        
        $exam->foreignNationals()->updateExistingPivot(
            $foreignNational->id,
            ['has_payment' => !$enrollment->pivot->has_payment]
        );
    }
}