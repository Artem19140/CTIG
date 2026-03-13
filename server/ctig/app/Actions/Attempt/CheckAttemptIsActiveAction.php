<?php

namespace App\Actions\Attempt;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Student;

class CheckAttemptIsActiveAction{
    public function execute(Student $student){
        $currentAttempt = Attempt::where('student_id', $student->id)
                            ->where('status', AttemptStatus::Active)->first();
        if(!$currentAttempt){
            return redirect('login')->with('У вас нет активной попытки экзамена');
        }
        if($currentAttempt->isExpired || !$currentAttempt->isActive()){
            $currentAttempt->finish();
            $currentAttempt->save();
            return redirect('login')->with('У вас нет активной попытки экзамена');
        }
    }
}