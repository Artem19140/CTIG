<?php

namespace App\Domain\Exam\Resolver;

use App\Enums\ExamStatus;
use App\Models\Exam;

class ExamStatusResolver{
    public function execute(Exam $exam):ExamStatus{
        
        if($exam->isCancelled()){
            return ExamStatus::Cancelled;
        }

        if($exam->isGoing()){
            return ExamStatus::Going;
        }
        if($exam->isFinished()){
            return ExamStatus::Finished;
        }

        return ExamStatus::Pending;
    }
}