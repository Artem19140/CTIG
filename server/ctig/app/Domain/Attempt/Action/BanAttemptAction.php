<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\User;
use App\Support\Log\BusinessLog;
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

        $this->log($user, $attempt);
    }

    protected function log(User $user, Attempt $attempt){
        BusinessLog::event('attempt_banned', [
            'user_id' => $user->id,
            'attempt_id' => $attempt->id
        ]);
    }
}