<?php

namespace App\Domain\ExamDocument;

use App\Models\Exam;  

class GenerateShortExamResultsAction{
    public function execute(Exam $exam){
        $exam->load(['type', 'enrollments.attempt', 'enrollment.foreignNational']);
    }
}