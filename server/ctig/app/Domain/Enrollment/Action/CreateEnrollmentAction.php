<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Counter\GenerateRegNumberAction;
use App\Domain\Exam\Guard\ExamEnrollmentGuard;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use App\Domain\Exam\Guard\ExamGuard;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


final class CreateEnrollmentAction{
    public function __construct(
        protected GenerateRegNumberAction $generateRegNumber,
        protected ExamGuard $examGuard,
        protected ExamEnrollmentGuard $examEnrollmentGuard
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

        $this->examEnrollmentGuard->ensureNotFullEnrollment($exam);
        $this->examEnrollmentGuard->ensureEnrollmentNotExists($exam, $foreignNational);
        
        $enrollmentsExists = Exam::whereBeginTimeLess($exam->end_time)
            ->whereEndTimeMore($exam->begin_time)
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