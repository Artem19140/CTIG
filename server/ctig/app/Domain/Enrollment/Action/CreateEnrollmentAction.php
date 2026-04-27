<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Counter\GenerateRegNumberAction;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use Carbon\Carbon;


final class CreateEnrollmentAction{
    public function __construct(
        protected GenerateRegNumberAction $generateRegNumber,
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function execute(int $examId, int $foreignNationalId, User $user, bool $hasPayment):Enrollment{
        $exam = Exam::find($examId);
        $foreignNational = ForeignNational::find($foreignNationalId);
        
        $this->ensureCreatingAvailable($exam, $foreignNational);
        
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

    protected function ensureCreatingAvailable(Exam $exam, ForeignNational $foreignNational){
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotGoing($exam);
        $closeBeforeMinutes = Enrollment::CLOSE_BEFORE_START_MINUTES;
        $enrollmentEnded = Carbon::now()->greaterThan($exam->begin_time->subMinutes($closeBeforeMinutes));
        if($enrollmentEnded){
            throw new BusinessException("Запись закрывается за $closeBeforeMinutes минут до начала экзамена");
        }
        $this->examGuard->ensureHasSeats($exam);
        $this->enrollmentGuard->ensureNotExists($exam, $foreignNational);
        $this->enrollmentGuard->ensureNoParallelEnrollments(
            $foreignNational, 
            $exam
        ); 
    }
}