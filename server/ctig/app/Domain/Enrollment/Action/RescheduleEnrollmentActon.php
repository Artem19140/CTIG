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

class RescheduleEnrollmentActon{
    public function __construct(
        protected CreateEnrollmentAction $createEnrollment,
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function execute(Enrollment $enrollment, int $toExamId, User $user):Enrollment{
        $toExam = Exam::find($toExamId);
        $foreignNational = ForeignNational::find($enrollment->foreign_national_id);
        
        $this->enrollmentGuard->ensureHasSeats($toExam, 'Нельзя перенести запись на экзамен, на котором полная запись');
        $this->enrollmentGuard->ensureNoParallelEnrollments($foreignNational, $toExam);

        if($enrollment->exam->examType->id !== $toExam->exam_type_id ){
            throw new BusinessException('Запись можно перенести только на тот же вид экзамена');
        }

        $this->examGuard->ensureNotCancelled($toExam);

        $this->examGuard->ensureNotCompleted($toExam);
        //$this->examGuard->ensureNotGoing($enrollment->exam, 'Запись нельзя перенести с идущего экзамена');

        $this->examGuard->ensureNotGoing($toExam);
        

        $enrollment = DB::transaction(function () use($toExam, $foreignNational, $user, $enrollment){
            if(!$enrollment->exam->begin_time_utc->isPast()){
                $enrollment->exam->foreignNationals()->detach($foreignNational->id); //Тут нужно сделать softDelete, а мб статус изменить
            } 
            return $this->createEnrollment->execute($toExam, $foreignNational->id, $user, $enrollment->hasPayment());
        });
        return $enrollment;
    }
}