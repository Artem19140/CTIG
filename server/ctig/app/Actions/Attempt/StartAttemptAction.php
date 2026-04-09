<?php

namespace  App\Actions\Attempt;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StartAttemptAction{
    public function execute(Attempt $attempt, ForeignNational $foreignNational):Attempt{
        return DB::transaction(function () use($attempt) {
            $exam=Exam::find($attempt->exam_id);
            $attempt->status = AttemptStatus::Active;
            $attempt->started_at = Carbon::now($exam->center->time_zone);
            $attempt->expired_at = Carbon::now()->addMinutes($exam->duration);
            $attempt->last_activity_at = Carbon::now($exam->center->time_zone);
            $attempt->save();
            return $attempt;
        });
        
    }
}