<?php

namespace App\Domain\Exam\Guard;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;


class ExamGuard{
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

    public function ensureHasSeats(Exam $exam, string $message = 'Запись на экзамен полная'):void{
        $enrollmentsCount = $exam->enrollments()
                                    ->whereHas('enrollments', function(Builder $query){
                                        $query->where('rescheduled_at', null)
                                            ->where('cancelled_at', null);
                                    })
                                    ->count();
        if($exam->capacity <= $enrollmentsCount){
            throw new BusinessException($message);
        }
    }

    public function ensureHasEnrollment(Exam $exam, string $message = 'На экзамен не записано ни одного ИГ'){
        if(!$exam->foreignNationals()->exists()){
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

    public function EnsureAllAttemptsChecked(Exam $exam, string $message = 'Экзамен отменен'){
        $attemptsNotChecked = $exam->attempts()
                                ->whereIn('status', AttemptStatus::unChecked())
                                ->exists();

        if($attemptsNotChecked){
            throw new BusinessException('Не все результаты экзамена еще проверены');
        }
    }

}