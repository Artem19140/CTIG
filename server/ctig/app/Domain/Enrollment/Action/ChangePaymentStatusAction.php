<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;


class ChangePaymentStatusAction{
    public function __construct(
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}

    public function execute(Exam  $exam, ForeignNational $foreignNational){
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotCompleted($exam);
        $this->enrollmentGuard->ensureExists($exam, $foreignNational);
        
        $enrollment = Enrollment::for($exam, $foreignNational)->first();
        $enrollment->changePaymentStatus();
        $enrollment->save();
    }
}