<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Counter\GenerateRegNumberAction;
use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use App\Actions\ForeignNational\CreateForeignNationalStatementAction;

final class CreateEnrollmentAction{
    public function __construct(
        protected CreateForeignNationalStatementAction $createForeignNationalStatement,
        protected GenerateRegNumberAction $generateRegNumber,
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function execute(Exam $exam, int $foreignNationalId, User $user, bool $hasPayment):Enrollment{
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotCompleted($exam);
        $this->examGuard->ensureNotGoing($exam);
        $this->enrollmentGuard->ensureHasSeats($exam);

        $foreignNational = ForeignNational::find($foreignNationalId);

        $this->enrollmentGuard->ensureNotExists($exam, $foreignNational);
        $this->enrollmentGuard->ensureNoParallelEnrollments(
                                                                $foreignNational, 
                                                                $exam
                                                            ); 

        $enrollment = Enrollment::create([
            'reg_number' => $this->generateRegNumber->execute(),
            'creator_id' => $user->id,
            'center_id' => $user->center_id,
            'has_payment' => $hasPayment,
            'exam_id' => $exam->id,
            'foreign_national_id' => $foreignNational->id
        ]);

        return $enrollment;
    }
}