<?php

namespace  App\Domain\Attempt\Action;

use App\Models\Attempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Domain\Attempt\Guard\AttemptGuard;
use Illuminate\Support\Facades\Log;

class StartAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt):Attempt{
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureNotStarted($attempt, 'Попытку можно начать только если она ещё не активна');
        return DB::transaction(function () use($attempt) {
            //Log::info($attempt->time_zone);
            $attempt->started_at = Carbon::now($attempt->time_zone);
            $attempt->expired_at = Carbon::now($attempt->time_zone)->addMinutes($attempt->exam->duration);
            $attempt->last_activity_at = Carbon::now($attempt->time_zone);
            $attempt->save();
            return $attempt;
        });
        
    }
}