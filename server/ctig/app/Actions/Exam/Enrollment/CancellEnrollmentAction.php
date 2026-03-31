<?php

namespace App\Actions\Exam\Enrollment;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Validation\ExamValidation;

class CancellEnrollmentAction{
    public function __construct(
        protected ExamValidation $examValidation
    ){}
        
    public function execute(Exam $exam, ForeignNational $foreignNational) {
        $this->examValidation->ensureNotCompleted($exam);
        $this->examValidation->ensureNotCancelled($exam);

        $isEnrollmentExists = $exam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->exists();
        if(!$isEnrollmentExists){
            throw new BusinessException('У ИГ нет записи на этот экзамен');
        }
        $exam->foreignNationals()->detach($foreignNational->id);
    }
}