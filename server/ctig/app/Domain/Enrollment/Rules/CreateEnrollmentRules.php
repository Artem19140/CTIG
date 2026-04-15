<?php 

namespace App\Domain\Enrollment\Rules;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Models\Exam;
use App\Models\ForeignNational;

class CreateEnrollmentRules{
    public function __construct(
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function execute(Exam $exam, ForeignNational $foreignNational):void{
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotGoing($exam);
        $this->examGuard->ensureHasSeats($exam);
        $this->enrollmentGuard->ensureNotExists($exam, $foreignNational);
        $this->enrollmentGuard->ensureNoParallelEnrollments(
                                                                $foreignNational, 
                                                                $exam
                                                            ); 
    }

}