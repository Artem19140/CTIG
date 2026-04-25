<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;

class CloseAbandonedAttemptsAction{
    public function __construct(
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction,
        protected ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswersAction
    ){}
    public function execute(string $timeZone):void{
        $now = Carbon::now($timeZone);
        
        $attemtps = Attempt::where('expired_at', '<=', $now)
            ->whereIn('status', AttemptStatus::abandoned())
            ->get();
        if($attemtps->isEmpty()){
            return;
        }
        foreach($attemtps as $attempt){
            $this->close($attempt);
        }
    }

    protected function close(Attempt $attempt){
        $this->zeroEmptyAutoAnswersAction->execute($attempt);
        if(!$attempt->hasUncheckedAnswers()){
            $attempt->finished_at = $attempt->last_activity_at ?? $attempt->expired_at;
            $this->finilizeAttemptCheckingAction->execute($attempt);
        }
    }
}