<?php

namespace App\Actions\Exam\Validation;

use App\Exceptions\BusinessException;
use App\Models\Exam;

class EnsureExamIsCompletedAction{
    public function execute(Exam $exam, string $message = 'Экзамен еще не прошел'){
        if(!$exam->isCompleted()){
            throw new BusinessException($message);
        }
    }
}