<?php

namespace App\Actions\Reports;

use App\Actions\Exam\Validation\EnsureExamIsCompletedAction;
use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;
use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;


class GenerateExamProtocolAction{
    public function __construct(
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled,
        protected EnsureExamIsCompletedAction $ensureExamIsCompleted
    ){}
    public function execute(Exam $exam, User $user){
        // $this->ensureExamIsNotCancelled->execute($exam);
        // $this->ensureExamIsCompfleted->execute($exam);
        $bannedAttempts = Attempt::with('foreignNational')
                        ->where('exam_id', $exam->id)
                        ->where('status', AttemptStatus::Banned)->get();
        $pdf = Pdf::loadView('templates.exam-protocol', [
            'exam' => $exam,
            'organization' => $user->organization, 
            'bannedAttempts' => $bannedAttempts
        ]);

        return $pdf->stream("codes.pdf");
    }
}