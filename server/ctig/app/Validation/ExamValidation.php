<?php

namespace App\Validation;

use App\Exceptions\BusinessException;
use App\Models\Exam;

class ExamValidation{
    public function ensureNotCompleted(Exam $exam, string $message = 'Экзамен уже прошел'){
        if($exam->isCompleted()){
            throw new BusinessException($message);
        }
    }

    public function ensureCompleted(Exam $exam, string $message = 'Экзамен еще не прошел'){
        if(!$exam->isCompleted()){
            throw new BusinessException($message);
        }
    }

    public function ensureHasEnrollment(Exam $exam, string $message = 'На экзамен не записано ни одного ИГ'){
        if(!$exam->foreignNationals()->exists()){
            throw new BusinessException($message);
        }
    }

    public function ensureGoing(Exam $exam, string $message ='Экзамен уже идет'){
        if(!$exam->isGoing()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotGoing(Exam $exam, string $message = 'Экзамен еще не начался'){
        if($exam->isGoing()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotCancelled(Exam $exam, string $message = 'Экзамен отменен'){
        if($exam->isCancelled()){
            throw new BusinessException($message);
        }
    }

}