<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Counter\GenerateRegNumberAction;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\Employee;
use App\Domain\Exam\Guard\ExamGuard;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


final class CreateEnrollmentAction{
    public function __construct(
        protected GenerateRegNumberAction $generateRegNumber,
        protected ExamGuard $examGuard,
    ){}
    public function execute(
        int $examId, 
        int $foreignNationalId, 
        Employee $creator, 
        bool $hasPayment
    ):Enrollment{
        $exam = Exam::find($examId);
        $foreignNational = ForeignNational::find($foreignNationalId);
        
        $this->ensureCreatingAvailable($exam, $foreignNational);
        
        $enrollment = Enrollment::create([
            'reg_number' => $this->generateRegNumber->execute(),
            'creator_id' => $creator->id,
            'center_id' => $exam->center_id,
            'has_payment' => $hasPayment,
            'exam_id' => $exam->id,
            'foreign_national_id' => $foreignNational->id
        ]);

        return $enrollment;
    }

    protected function ensureCreatingAvailable(
        Exam $exam, 
        ForeignNational $foreignNational
    ):void{
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensurePending($exam, "Записать на экзамен возможна только до его начала");
        $this->ensureEnrollementWindowNotClosed($exam);
        $this->ensureEnrollmentNotExists($exam, $foreignNational);
        $this->ensureParallellEnrollmentsNotExists($exam, $foreignNational);
        $this->ensureNotFullEnrollment($exam);
    }

    protected function ensureEnrollementWindowNotClosed(Exam $exam):void{
        $closeBeforeMinutes = Enrollment::CLOSE_BEFORE_START_MINUTES;
        $enrollmentEnded = Carbon::now()->greaterThan($exam->begin_time->subMinutes($closeBeforeMinutes));
        if($enrollmentEnded){
            throw new BusinessException("Запись закрывается за $closeBeforeMinutes минут до начала экзамена");
        }
    }

    protected function ensureParallellEnrollmentsNotExists(
        Exam $exam, 
        ForeignNational $foreignNational
    ):void{
        $parallellEnrollmentsExists = Exam::query()
            ->where('begin_time', '<',$exam->end_time)
            ->where('end_time', '>', $exam->begin_time)
            ->notCancelled()
            ->whereHas('enrollments', function(Builder $query)use($foreignNational){
                $query->where('foreign_national_id', $foreignNational->id);
            })
            ->exists();

        if($parallellEnrollmentsExists){
            throw new BusinessException('ИГ имеет парралельные записи на экзамен');
        }
    }

    public function ensureNotFullEnrollment(
        Exam $exam
    ):void{
        $enrollmentsCount = $exam->enrollments()->count();
        if($exam->capacity <= $enrollmentsCount){
            throw new BusinessException('Запись на экзамен полная');
        }
    }

    public function ensureEnrollmentNotExists(
        Exam $exam, 
        ForeignNational $foreignNational
    ):void{
        $exists = Enrollment::query()
            ->where('exam_id', $exam->id)
            ->where('foreign_national_id', $foreignNational->id)
            ->exists();

        if($exists){
            throw new BusinessException('Запись на экзамен уже сущестует');
        }
    }
}