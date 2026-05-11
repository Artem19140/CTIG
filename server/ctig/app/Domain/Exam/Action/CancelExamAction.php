<?php

namespace App\Domain\Exam\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Enums\Event;
use App\Enums\Resource;
use App\Models\Exam;
use App\Support\Log\LogActivity;
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
        $exam->cancelled_at = Carbon::now();
        $exam->save();
        $this->log($exam);
    }

    protected function log(Exam $exam){
        LogActivity::event(
            event:Event::Updated,
            resource:Resource::Exam,
            context:[
                'exam_id' => $exam->id,
                'status' => 'cancelled'
            ]
        );
    }
}