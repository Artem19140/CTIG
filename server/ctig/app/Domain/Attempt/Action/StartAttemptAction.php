<?php

namespace  App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Domain\Attempt\Guard\AttemptGuard;

class StartAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt):Attempt{
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureNotStarted($attempt, 'Попытку можно начать только если она ещё не активна');

        return DB::transaction(function () use($attempt) {
            $now =  Carbon::now();
            $attempt->started_at = $now;
            $attempt->expired_at = $now->copy()->addMinutes($attempt->exam->duration);
            $attempt->last_activity_at = $now;
            $attempt->status=AttemptStatus::Active;
            $attempt->save();
            return $attempt;
        });
        
    }
}