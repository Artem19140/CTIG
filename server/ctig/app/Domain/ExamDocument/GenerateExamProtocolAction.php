<?php

namespace App\Domain\ExamDocument;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use App\Validation\ExamValidation;
use Barryvdh\DomPDF\Facade\Pdf;


class GenerateExamProtocolAction{
    public function __construct(
        protected ExamValidation $examValidation
    ){}
    public function execute(Exam $exam, User $user){
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureCompleted($exam);
        $bannedAttempts = Attempt::with('foreignNational')
                        ->where('exam_id', $exam->id)
                        ->where('status', AttemptStatus::Banned)->get();
        $pdf = Pdf::loadView('templates.exam-protocol', [
            'exam' => $exam,
            'center' => $user->center, 
            'bannedAttempts' => $bannedAttempts
        ]);

        return $pdf->stream("codes.pdf");
    }
}