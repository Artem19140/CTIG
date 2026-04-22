<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;

class FinilizeAttemptCheckingAction{
    public function __construct(
        protected CheckPassingThresholdAction $checkPassingThreshold
    ){}
    public function execute(Attempt $attempt):Attempt{
        $attempt->status = AttemptStatus::Checked;
        $attempt->checked_at = Carbon::now($attempt->time_zone);
        $attempt->total_mark = $attempt->answers()->sum('mark');
        $isPassed = $this->checkPassingThreshold->execute($attempt);
        $attempt->is_passed = $isPassed;
        $attempt->save();
        return $attempt;
    }
}