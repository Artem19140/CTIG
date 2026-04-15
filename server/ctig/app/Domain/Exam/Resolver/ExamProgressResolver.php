<?php

namespace App\Domain\Exam\Resolver;

use App\Enums\ExamStatus;
use App\Models\Enrollment;
use App\Models\Exam;

class ExamProgressResolver{
    public function execute(Enrollment $enrollment):string{
        $enrollment->loadMissing(['exam', 'attempt']);
        $exam = $enrollment->exam;
        $attempt = $enrollment->attempt;
        
        if($enrollment->isCancelled()){
            return 'cancelled';
        }

        if($enrollment->isRescheduled()){
            return 'rescheduled';
        }

        if($enrollment->exam->isCancelled()){
            return 'exam_cancelled';
        }
        //-----
        if(!$attempt && $exam->isCompleted()){
            return 'absent';
        }


        if($attempt && $exam->isGoing()){
            return 'going';
        }


        if($attempt?->isBanned()){
            return 'banned';
        }

        if($attempt?->isFinished()){
            return 'finished';
        }

        return $attempt?->isSuccessful() ?  'successed' : 'failed';

        return 'pending';
    }
}