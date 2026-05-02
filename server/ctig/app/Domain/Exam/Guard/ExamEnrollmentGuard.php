<?php

namespace App\Domain\Exam\Guard;

use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;


class ExamEnrollmentGuard{

    public function ensureNotFullEnrollment(Exam $exam, string $message = 'Запись на экзамен полная'):void{
        $enrollmentsCount = $exam->enrollments()->count();
        if($exam->capacity <= $enrollmentsCount){
            throw new BusinessException($message);
        }
    }

    public function ensureEnrollmentsExists(Exam $exam, string $message = 'На экзамен не записано ни одного ИГ'){
        if(!$exam->enrollments()->exists()){
            throw new BusinessException($message);
        }
    }

    public function ensureEnrollmentNotExists(Exam $exam, ForeignNational $foreignNational, string $message = 'Запись на экзамен уже сущестует'){
        $exists = Enrollment::for($exam, $foreignNational)->exists();
        if($exists){
            throw new BusinessException($message);
        }
    }
}