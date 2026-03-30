<?php

namespace App\Actions\Exam\Validation;

use App\Exceptions\BusinessException;
use App\Models\Exam;

class EnsureExamHasEnrollmentAction{
    public function execute(Exam $exam, string $message = 'На экзамен не записано ни одного ИГ'){
        $foreignNationalsExists = $exam->foreignNationals()->exists();
        if(!$foreignNationalsExists){
            throw new BusinessException($message);
        }
    }
}