<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;


class CancellEnrollmentAction{
    public function __construct(
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
        
    public function execute(Enrollment $enrollment) {
        //enrollment soft delete
        $exam = Exam::find($enrollment->exam_id);
        $foreignNational = ForeignNational::find($enrollment->foreign_national_id);
        $this->examGuard->ensureNotCompleted($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->enrollmentGuard->ensureExists($exam, $foreignNational);

        $exam->foreignNationals()->detach($foreignNational->id);
    }
}