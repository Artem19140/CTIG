<?php

namespace  App\Actions\Attempt;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;

class StartAttemptAction{
    public function execute(Attempt $attempt, Student $student):Attempt{
        $exam=Exam::find($student->exam_id);
        $attempt->lockForUpdate();
        $attempt->status = AttemptStatus::Active;
        $attempt->started_at = Carbon::now($exam->organisation->time_zone);
        $attempt->expired_at = Carbon::now($exam->organisation->time_zone)->addMinutes($exam->duration);
        $attempt->last_activity_at = Carbon::now($exam->organisation->time_zone);
        $attempt->save();
        return $attempt;
    }
}