<?php

namespace App\Domain\Exam\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use Carbon\Carbon;

class CancelExamAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    
    public function execute(Exam $exam, string $reason):void{
        $this->examGuard->ensureNotCancelled($exam,'Экзамен уже отменен');
        $this->ensureNotPending($exam);
        
        $exam->cancelled_reason = $reason;
        $exam->cancelled_at = Carbon::now();
        $exam->save();
    }

    protected function ensureNotPending(Exam $exam,){
        if(!$exam->isPending()){
            throw new BusinessException('Экзамен возможно отменить до его начала');
        }
    }
}