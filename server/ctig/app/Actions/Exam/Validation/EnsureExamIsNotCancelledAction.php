<?php

namespace App\Actions\Exam\Validation;

use App\Exceptions\BusinessException;
use App\Models\Exam;

class EnsureExamIsNotCancelledAction{
    public function execute(Exam $exam, string $message = 'Экзамен отменен'){
        if($exam->isCancelled()){
            throw new BusinessException($message);
        }
    }
}