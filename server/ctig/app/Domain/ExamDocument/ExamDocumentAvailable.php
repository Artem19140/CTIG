<?php

namespace App\Domain\ExamDocument;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;

class ExamDocumentAvailable{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    public function codes(Exam $exam){
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureHasEnrollment($exam);
        if(!$exam->canGenerateCodes()){
            $minutes = Exam::CODES_AVAILABLE_BEFORE_MINUTES;
            throw new BusinessException("Коды можно сформировать минимум за $minutes минут до экзамена");
        }
    }

    public function list(Exam $exam){
        $this->examGuard->ensureHasEnrollment($exam);
    }

    public function protocol(Exam $exam){
        $this->examGuard->ensureFinished($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureHasEnrollment($exam);
    }

    public function statement(Exam $exam){
        $this->examGuard->ensureFinished($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureHasEnrollment($exam);
        $this->examGuard->ensureAllAttemptsChecked($exam);
    }

    public function results(Exam $exam){
        $this->examGuard->ensureFinished($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureHasEnrollment($exam);
        $this->examGuard->ensureAllAttemptsChecked($exam);
    }
}