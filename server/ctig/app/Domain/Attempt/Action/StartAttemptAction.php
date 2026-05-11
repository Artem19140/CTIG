<?php

namespace  App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Enums\Event;
use App\Enums\Resource;
use App\Models\Attempt;
use App\Support\Log\LogActivity;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use App\Domain\Attempt\Guard\AttemptGuard;

class StartAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt):Attempt{
        $this->attemptGuard->ensureNotBanned($attempt);

        return DB::transaction(function () use($attempt) {
            $now = Carbon::now();
            $attempt->started_at = $now;
            $attempt->expired_at = $now->copy()->addMinutes($attempt->exam->duration);
            $attempt->last_activity_at = $now;
            $attempt->status=AttemptStatus::Active;
            $attempt->save();
            $this->log($attempt);
            return $attempt;
        });
        
    }

    protected function log(Attempt $attempt){
        LogActivity::event(
            event: Event::Updated,
            resource:Resource::Attempt,
            context:[
                'attempt_id' => $attempt->id,
                'status' => AttemptStatus::Active
            ]);
    }
}