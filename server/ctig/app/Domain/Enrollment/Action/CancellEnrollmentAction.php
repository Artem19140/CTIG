<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use Carbon\Carbon;


class CancellEnrollmentAction{
    public function __construct(
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
        
    public function execute(Enrollment $enrollment) {
        //enrollment soft delete
        $exam = Exam::find($enrollment->exam_id);
        $foreignNational = ForeignNational::find($enrollment->foreign_national_id);
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->enrollmentGuard->ensureExists($exam, $foreignNational);

        //$exam->foreignNationals()->detach($foreignNational->id);
        $enrollment->cancelled_at = Carbon::now($enrollment->time_zone);
        $enrollment->save();
    }
}