<?php

namespace App\Domain\ExamDocument;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use Carbon\Carbon;
use Log;

class ExamDocumentAvailable{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    public function codes(Exam $exam){
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureHasEnrollment($exam);
        if(!Carbon::now()->gte($exam->begin_time_utc->subMinutes(Exam::CODES_AVAILABLE_BEFORE_MINUTES))){
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
        $this->examGuard->EnsureAllAttemptsChecked($exam);
    }

    public function results(Exam $exam){
        $this->examGuard->ensureFinished($exam);
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureHasEnrollment($exam);
        $this->examGuard->EnsureAllAttemptsChecked($exam);
    }
}