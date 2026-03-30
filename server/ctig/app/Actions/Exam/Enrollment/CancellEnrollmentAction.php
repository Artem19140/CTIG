<?php

namespace App\Actions\Exam\Enrollment;

use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;
use App\Actions\Exam\Validation\EnsureExamIsNotCompletedAction;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;

class CancellEnrollmentAction{
    public function __construct(
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled,
        protected EnsureExamIsNotCompletedAction $ensureExamIsNotCompleted
    ){}
        
    public function execute(Exam $exam, ForeignNational $foreignNational) {
        $this->ensureExamIsNotCompleted->execute($exam);
        $this->ensureExamIsNotCancelled->execute($exam);

        $isEnrollmentExists = $exam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->exists();
        if(!$isEnrollmentExists){
            throw new BusinessException('У ИГ нет записи на этот экзамен');
        }
        $exam->foreignNationals()->detach($foreignNational->id);
    }
}