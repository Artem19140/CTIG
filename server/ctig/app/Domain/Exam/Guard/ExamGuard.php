<?php

namespace App\Domain\Exam\Guard;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Exam;


class ExamGuard{
    public function ensureNotFinished(Exam $exam, string $message = 'Экзамен уже прошел'){
        if($exam->isFinished()){
            throw new BusinessException($message);
        }
    }

    public function ensureFinished(Exam $exam, string $message = 'Экзамен еще не прошел'){
        if(!$exam->isFinished()){
            throw new BusinessException($message);
        }
    }

    public function ensureGoing(Exam $exam, string $message ='Экзамен еще не идет'){
        if(!$exam->isGoing()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotGoing(Exam $exam, string $message = 'Экзамен уже идет'){
        if($exam->isGoing()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotCancelled(Exam $exam, string $message = 'Экзамен отменен'){
        if($exam->isCancelled()){
            throw new BusinessException($message);
        }
    }

    public function ensureAllAttemptsChecked(Exam $exam, string | null $message = null){
        //loadExists
        $attemptsNotChecked = $exam->attempts()
            ->statusUnchecked()
            ->exists();

        if($attemptsNotChecked){
            throw new BusinessException($message ?? 'Не все результаты экзамена еще проверены');
        }
    }
}