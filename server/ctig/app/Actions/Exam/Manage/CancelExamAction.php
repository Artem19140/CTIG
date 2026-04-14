<?php

namespace App\Actions\Exam\Manage;

use App\Models\Exam;
use App\Validation\ExamValidation;
use Carbon\Carbon;
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
        $exam->cancelled_at = Carbon::now($exam->time_zone);
        $exam->save();
    }
}