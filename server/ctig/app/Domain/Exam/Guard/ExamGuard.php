<?php

namespace App\Domain\Exam\Guard;

use App\Exceptions\BusinessException;
use App\Models\Exam;


class ExamGuard{
    public function ensureNotFinished(Exam $exam, string $message = 'Экзамен уже прошел'){
        if($exam->isFinished()){
            throw new BusinessException($message);
        }
    }
    public function ensurePending(Exam $exam, string $message = 'Экзамен еще не начался'){
        if(!$exam->isPending()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotCancelled(Exam $exam, string $message = 'Экзамен отменен'){
        if($exam->isCancelled()){
            throw new BusinessException($message);
        }
    }
}