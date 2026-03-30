<?php

namespace App\Actions\Exam;

use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;
use App\Actions\Exam\Validation\EnsureExamIsNotCompletedAction;
use App\Actions\Exam\Validation\EnsureExamIsNotGoingAction;
use App\Models\Exam;
class CancelExamAction{
    public function __construct(
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled,
        protected EnsureExamIsNotCompletedAction $ensureExamIsNotCompleted,
        protected EnsureExamIsNotGoingAction $ensureExamIsNotGoing
    ){}
    public function execute(Exam $exam){
        $this->ensureExamIsNotCancelled->execute($exam,'Экзамен уже отменен');
        $this->ensureExamIsNotCompleted->execute($exam);
        $this->ensureExamIsNotGoing->execute($exam);

        //Всем гражданам поставить запись - отменено
        
        $exam->cancelled_reason = request()->input('cancelledReason');
        $exam->is_cancelled = true;
        $exam->save();
    }
}