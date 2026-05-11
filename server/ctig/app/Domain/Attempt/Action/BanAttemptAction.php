<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Enums\Event;
use App\Enums\Resource;
use App\Models\Attempt;
use App\Models\User;
use App\Support\Log\LogActivity;
use Carbon\Carbon;
use App\Domain\Attempt\Guard\AttemptGuard;

class BanAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt, string $banReason, User $user){
        $this->attemptGuard->ensureNotBanned($attempt);

        $attempt->ban_reason = $banReason;
        $attempt->ban_by_id = $user->id;
        $attempt->status = AttemptStatus::Banned;
        $attempt->banned_at = Carbon::now();
        
        $attempt->save();

        $this->log($attempt);
    }

    protected function log( Attempt $attempt){
        LogActivity::event(
            event:Event::Updated,
            resource:Resource::Attempt,
                context:[
                'attempt_id' => $attempt->id,
                'status' => AttemptStatus::Banned
            ]);
    }
}