<?php

namespace App\Actions\Attempt;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\TaskVariant;
use App\Validation\AttemptValidation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BanAttemptAction{
    public function __construct(
        protected AttemptValidation $attemptValidation
    ){}
    public function execute(Attempt $attempt, string $banReason, int $banById){
        $this->attemptValidation->ensureNotBanned($attempt);
        $attempt->ban_reason = $banReason;
        $attempt->ban_by_id = $banById;
        $attempt->is_passed = false;
        $attempt->finished_at = Carbon::now();
        $attempt->status = AttemptStatus::Banned;
        $attempt->save();
    }
}