<?php

namespace App\Domain\ExamDocument;

use App\Domain\Exam\Guard\ExamEnrollmentGuard;
use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;

class ExamDocumentAvailable{
    public function __construct(
        protected ExamGuard $examGuard,
        protected ExamEnrollmentGuard $examEnrollmentGuard
    ){}
    public function codes(Exam $exam){
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotFinished($exam);
        $this->examEnrollmentGuard->ensureEnrollmentsExists($exam);
        if(!$exam->canGenerateCodes()){
            $minutes = Exam::CODES_TTL_AFTER_BEGIN_MINUTES;
            throw new BusinessException("Коды возможно сформировать в день экзамена и в течении $minutes минут после его начала");
        }
    }

    public function list(Exam $exam){
        $this->examEnrollmentGuard->ensureEnrollmentsExists($exam);
    }

    public function protocol(Exam $exam){
        $this->examGuard->ensureNotCancelled($exam);
        $this->examEnrollmentGuard->ensureEnrollmentsExists($exam);
        //Что попытки проверены и оценены
    }

    public function results(Exam $exam){
        $this->examGuard->ensureNotCancelled($exam);
        $this->examEnrollmentGuard->ensureEnrollmentsExists($exam);
        $this->examGuard->ensureAllAttemptsChecked($exam);
    }
}