<?php

namespace App\Domain\Enrollment\Rules;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\Exam;


class RescheduleEnrollmentRules{
    public function __construct(
        protected ExamGuard $examGuard,
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function execute(Enrollment $enrollment, Exam $toExam){
        $this->examGuard->ensureNotGoing($enrollment->exam, 'Запись нельзя перенести с начатого экзамена');
        $this->examGuard->ensureNotCompleted($enrollment->exam, 'Запись нельзя перенести с прошедшего экзамена');
        $this->examGuard->ensureNotCancelled($toExam, 'Запись нельзя перенести на отмененный экзамен');
        $this->examGuard->ensureNotCompleted($toExam, 'Запись нельзя перенести на прошедший экзамен');
        $this->examGuard->ensureNotGoing($toExam, 'Запись нельзя перенести на прошедший экзамен');
        $this->examGuard->ensureHasSeats($toExam, 'Запись нельзя перенести на экзамен с полной записью');
        $this->enrollmentGuard->ensureNoParallelEnrollments($enrollment->foreignNational, $toExam);
        if($enrollment->exam->examType->id !== $toExam->exam_type_id ){
            throw new BusinessException('Запись можно перенести только на тот же вид экзамена');
        }
    }
}