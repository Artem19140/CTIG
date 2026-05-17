<?php

namespace App\Domain\Attempt\Action;

use App\Models\Attempt;
use App\Models\Employee;
use App\Domain\Attempt\Guard\AttemptGuard;

class BanAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt, string $banReason, Employee $employee):void{
        $this->attemptGuard->ensureNotBanned($attempt);
        $attempt->finished_at = $attempt->last_activity_at;
        $attempt->ban_reason = $banReason;
        $attempt->ban_by_id = $employee->id;
        $attempt->ban();
        $attempt->save();
    }
}