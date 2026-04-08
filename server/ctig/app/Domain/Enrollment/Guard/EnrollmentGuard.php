<?php

namespace App\Domain\Enrollment\Guard;

use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentGuard{
    public function ensureNotExists(Exam $exam, ForeignNational $foreignNational, string $message = 'Запись на экзамен уже сущестует'):void{
        $exists = Enrollment::for($exam, $foreignNational)->exists();
        if($exists){
            throw new BusinessException($message);
        }
    }

    public function ensureExists(Exam $exam, ForeignNational $foreignNational, string $message = 'Запись на экзамен не существует'):void{
        $exists = Enrollment::for($exam, $foreignNational)->exists();
        if(!$exists){
            throw new BusinessException($message );
        }
    }

    public function ensureHasPayment(Enrollment $enrollment, string $message = 'Отсутствует оплата'):void{
        if(!$enrollment->hasPayment()){
            throw new BusinessException($message);
        }
    }

    public function ensureHasSeats(Exam $exam, string $message = 'Запись на экзамен полная'):void{
        $enrollmentsCount = $exam->enrollments()->count();
        if($exam->capacity <= $enrollmentsCount){
            throw new BusinessException($message);
        }
    }

    public function ensureNoParallelEnrollments(ForeignNational $foreignNational, Exam $exam){
        $enrollmentsExists = Exam::before($exam->end_time)
            ->after($exam->begin_time)
            ->notCancelled()
            ->whereHas('enrollments', function(Builder $query)use($foreignNational){
                $query->where('foreign_national_id', $foreignNational->id);
            })
            ->exists();
        if($enrollmentsExists){
            throw new BusinessException('ИГ имеет парралельные записи на экзамен');
        }
    }
}