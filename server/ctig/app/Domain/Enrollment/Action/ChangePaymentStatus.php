<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Exam\Rules\ExamValidation;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;


class ChangePaymentStatusAction{
    public function __construct(
        protected ExamValidation $examValidation
    ){}

    public function execute(Exam  $exam, ForeignNational $foreignNational){
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureNotCompleted($exam);
        
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