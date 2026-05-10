<?php

namespace App\Domain\Exam\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Models\Exam;
use App\Models\User;
use App\Support\Log\BusinessLog;
use Carbon\Carbon;

class CancelExamAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    public function execute(Exam $exam, User $author){
        $this->examGuard->ensureNotCancelled($exam,'Экзамен уже отменен');
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotGoing($exam);
        
        $exam->cancelled_reason = request()->input('cancelledReason');
        $exam->cancelled_at = Carbon::now();
        $exam->save();
        BusinessLog::event('exam_cancelled', [
            'exam_id' => $exam->id,
            'user_id' => $author->id
        ]);
    }
}