<?php

namespace App\Actions\Exam\Validation;

use App\Exceptions\BusinessException;
use App\Models\Exam;

class EnsureExamIsGoingAction{
    public function execute(Exam $exam, string $message = 'Экзамен уже идет'){
        if($exam->isGoing()){
            throw new BusinessException($message);
        }
    }
}