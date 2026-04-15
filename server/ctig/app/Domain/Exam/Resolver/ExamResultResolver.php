<?php

namespace App\Domain\Exam\Resolver;

use App\Enums\ExamResultStatus;
use App\Models\Enrollment;

class ExamResultResolver{
    public function execute(Enrollment $enrollment){
        $exam = $enrollment->exam;
        $attempt = $enrollment->attempt;

        if (!$exam->isFinished() || $exam->isCancelled()) {
            return null;
        }

        if(!$attempt){
            return ExamResultStatus::Absent; 
        }

        if($attempt->isBanned()){
            return ExamResultStatus::Banned; 
        }

        if($attempt->is_passed === true){
            return ExamResultStatus::Passed; 
        }

        if($attempt->is_passed === false){
            return ExamResultStatus::Failed; 
        }
        return null;
    }
}