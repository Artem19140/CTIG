<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;
use Server\Ctig\App\Domain\Attempt\Guard\AttemptGuard;

class BanAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt, string $banReason, int $banById){
        $this->attemptGuard->ensureNotBanned($attempt);
        $attempt->ban_reason = $banReason;
        $attempt->ban_by_id = $banById;
        $attempt->is_passed = false;
        $attempt->finished_at = Carbon::now(); //Если можно потом попытку аннулировать, то тут как быть?
        $attempt->status = AttemptStatus::Banned;
        $attempt->save();
    }
}