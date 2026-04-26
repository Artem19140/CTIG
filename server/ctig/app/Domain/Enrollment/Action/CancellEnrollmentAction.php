<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;


class CancellEnrollmentAction{
    public function __construct(
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
        
    public function execute(Enrollment $enrollment) {
        $exam = Exam::find($enrollment->exam_id);

        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotGoing($exam);


        if($enrollment->attempt()->exists()){
            throw new BusinessException('Нельзя отменить запись, если сущестует попытка экзамена');
        }

        //$enrollment->cancelled_at = Carbon::now($enrollment->time_zone);
        $enrollment->delete();
        $enrollment->save();
    }
}