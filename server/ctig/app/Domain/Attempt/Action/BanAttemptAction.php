<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;
use App\Domain\Attempt\Guard\AttemptGuard;

class BanAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt, string $banReason, int $banById){
        $this->attemptGuard->ensureNotBanned($attempt, 'Попытка уже аннулирована');

        $attempt->ban_reason = $banReason;
        $attempt->ban_by_id = $banById;
        $attempt->status = AttemptStatus::Banned;
        $attempt->banned_at = Carbon::now();
        
        $attempt->save();
    }
}