<?php

namespace App\Domain\Exam\Action;

use App\Models\Exam;
use App\Validation\ExamValidation;
class CancelExamAction{
    public function __construct(
        protected ExamValidation $examValidation
    ){}
    public function execute(Exam $exam){
        $this->examValidation->ensureNotCancelled($exam,'Экзамен уже отменен');
        $this->examValidation->ensureNotCompleted($exam);
        $this->examValidation->ensureNotGoing($exam);

        //Всем гражданам поставить запись - отменено
        
        $exam->cancelled_reason = request()->input('cancelledReason');
        $exam->is_cancelled = true;
        $exam->save();
    }
}