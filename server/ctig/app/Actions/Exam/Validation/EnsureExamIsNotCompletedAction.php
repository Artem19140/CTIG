<?php

namespace App\Actions\Exam\Validation;

use App\Exceptions\BusinessException;
use App\Models\Exam;

class EnsureExamIsNotCompletedAction{
    public function execute(Exam $exam, string $message = 'Экзамен уже прошел'){
        if($exam->isCompleted()){
            throw new BusinessException($message);
        }
    }
}