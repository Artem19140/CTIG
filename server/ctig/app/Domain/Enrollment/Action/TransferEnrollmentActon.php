<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use DB;

class TransferEnrollmentActon{
    public function __construct(
        protected CreateEnrollmentAction $createEnrollment,
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function exectute(int $fromExamId, int $toExamId, ForeignNational $foreignNational, User $user){
        $toExam = Exam::find($toExamId);
        $fromExam = Exam::find($fromExamId);

        $this->enrollmentGuard->ensureExists($fromExam, $foreignNational, 'Запись не существует, чтобы ее перенести');
        $this->enrollmentGuard->ensureNotExists($toExam, $foreignNational, 'Запись на другом экзамене уже существует, перенос невозможен');
        
        $this->enrollmentGuard->ensureHasSeats($toExam, 'Нельзя перенести запись на экзамен, на котором полная запись');
        $this->enrollmentGuard->ensureNoParallelEnrollments($foreignNational, $toExam);

        if($fromExam->exam_type_id !== $toExam->exam_type_id ){
            throw new BusinessException('Запись можно перенести только на тот же вид экзамена');
        }

        $this->examGuard->ensureNotCancelled($fromExam);
        $this->examGuard->ensureNotCancelled($toExam);

        $this->examGuard->ensureNotCompleted($fromExam);
        $this->examGuard->ensureNotCompleted($toExam);
        
        $this->examGuard->ensureNotGoing($fromExam);
        $this->examGuard->ensureNotGoing($toExam);
        
        $oldEnrollment = Enrollment::for($fromExam, $foreignNational)->first();

        DB::transaction(function () use($toExam, $foreignNational, $fromExam, $user, $oldEnrollment){
            $fromExam->foreignNationals()->detach($foreignNational->id); //Тут нужно сделать softDelete, а мб статус изменить
            $this->createEnrollment->execute($toExam, $foreignNational->id, $user, $oldEnrollment->hasPayment());
        });
    }
}