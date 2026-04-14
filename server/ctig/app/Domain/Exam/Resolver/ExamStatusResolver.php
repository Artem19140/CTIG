<?php

namespace App\Domain\Exam\Resolver;

use App\Enums\ExamStatus;
use App\Models\Exam;

class ExamStatusResolver{
    public function execute(Exam $exam):ExamStatus{
        
        if($exam->cancelled_at){
            return ExamStatus::Cancelled;
        }

        if($exam->isGoing()){
            return ExamStatus::Going;
        }
        if($exam->isCompleted()){
            return ExamStatus::Completed;
        }

        return ExamStatus::Pending;
    }
}