<?php

namespace App\Actions\Exam\Validation;

use App\Exceptions\BusinessException;
use App\Models\Exam;

class EnsureExamIsNotGoingAction{
    public function execute(Exam $exam, string $message = 'Экзамен еще не начался'){
        if(!$exam->isGoing()){
            throw new BusinessException($message);
        }
    }
}