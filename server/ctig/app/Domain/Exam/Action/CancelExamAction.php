<?php

namespace App\Domain\Exam\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Models\Exam;
use Carbon\Carbon;

class CancelExamAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    public function execute(Exam $exam){
        $this->examGuard->ensureNotCancelled($exam,'Экзамен уже отменен');
        $this->examGuard->ensureNotFinished($exam);
        $this->examGuard->ensureNotGoing($exam);
        
        $exam->cancelled_reason = request()->input('cancelledReason');
        $exam->cancelled_at = Carbon::now($exam->time_zone);
        $exam->save();
    }
}