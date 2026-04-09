<?php

namespace  App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Domain\Attempt\Guard\AttemptGuard;

class StartAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt, ForeignNational $foreignNational):Attempt{
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensurePending($attempt, 'Попытку можно начать только если она ещё не активна');
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