<?php

namespace App\Domain\Attempt\Action;

use App\Models\Attempt;
use App\Models\User;
use App\Domain\Attempt\Guard\AttemptGuard;

class BanAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt, string $banReason, User $user):void{
        $this->attemptGuard->ensureNotBanned($attempt);

        $attempt->ban_reason = $banReason;
        $attempt->ban_by_id = $user->id;
        $attempt->ban();
        $attempt->save();
    }
}