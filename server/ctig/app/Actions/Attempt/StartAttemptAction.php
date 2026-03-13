<?php

namespace  App\Actions\Attempt;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;

class StartAttemptAction{
    public function execute(Attempt $attempt, Student $student){
        $exam=Exam::find($student->exam_id);
        $attempt->lockForUpdate();
        $attempt->status = AttemptStatus::Active;
        $attempt->started_at = Carbon::now();
        $attempt->expired_at = Carbon::now()->addMinutes($exam->duration);
        $attempt->last_activity_at = Carbon::now();
        $attempt->save();
        return $attempt;
    }
}