<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;

class CloseAbandonedAttemptsAction{
    public function __construct(
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){}
    public function execute():void{
        $now = Carbon::now();
        
        Attempt::where('expired_at', '<=', $now)
            ->with(['exam.type'])
            ->where('status', AttemptStatus::Active)
            ->get()
            ->each(function($attempt){
                $this->close($attempt);
            });
    }

    protected function close(Attempt $attempt){
        if ($attempt->finished_at !== null) {
            return;
        }
        $attempt->finished_at = $attempt->last_activity_at;
        if ($attempt->canBeAutomaticallyFinalized()) {
            $this->finilizeAttemptCheckingAction->execute($attempt);
        } else {
            $attempt->status = AttemptStatus::Finished;
            $attempt->save();
        }
    }
}