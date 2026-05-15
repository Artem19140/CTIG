<?php

namespace App\Domain\Attempt\Action;

use App\Models\Attempt;
use Carbon\Carbon;

class FinilizeAttemptCheckingAction{
    public function __construct(
        protected CheckPassingThresholdAction $checkPassingThreshold
    ){}
    public function execute(Attempt $attempt):Attempt{
        
        $attempt->total_mark = $attempt->answers()->sum('mark');
        $attempt->is_passed = $this->checkPassingThreshold->execute($attempt);

        if($this->attemptNotBanned($attempt)){
            $attempt->markAsChecked();
        }else{
            $attempt->checked_at = Carbon::now();
        }
        $attempt->save();
        return $attempt;
    }

    protected function attemptNotBanned(Attempt $attempt):bool{
        return !$attempt->isBanned();
    }
}