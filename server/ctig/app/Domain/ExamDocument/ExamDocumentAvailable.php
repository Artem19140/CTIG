<?php

namespace App\Domain\ExamDocument;

use App\Domain\Exam\Rules\ExamValidation;
use App\Models\Exam;

class ExamDocumentAvailable{
    public function __construct(
        protected ExamValidation $examValidation
    ){}
    public function codes(Exam $exam){
        $this->examValidation->ensureNotCompleted($exam);
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureHasEnrollment($exam);
    }

    public function list(Exam $exam){
        $this->examValidation->ensureHasEnrollment($exam);
    }

    public function protocol(Exam $exam){
        $this->examValidation->ensureCompleted($exam);
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureHasEnrollment($exam);
    }

    public function statement(Exam $exam){
        $this->examValidation->ensureCompleted($exam);
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureHasEnrollment($exam);
        $this->examValidation->EnsureAllAttemptsChecked($exam);
    }
}