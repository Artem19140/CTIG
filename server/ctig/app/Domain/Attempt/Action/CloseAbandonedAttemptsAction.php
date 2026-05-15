<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CloseAbandonedAttemptsAction{
    public function __construct(
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){}
    public function execute():void{
        $now = Carbon::now();
        
        Attempt::where('expired_at', '<=', $now)
            ->with(['exam.type'])
            ->statusActive()
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
        $attempt->status = AttemptStatus::Finished;
        if ($attempt->canBeAutomaticallyFinalized()) {
            $this->finilizeAttemptCheckingAction->execute($attempt);
        } 
        $attempt->save();
        $this->log($attempt);
    }

    protected function log(Attempt $attempt){
        Log::info('', [
            'attempt_id' => $attempt->id,
            'status' => $attempt->status,
            'cron'
        ]);
    }
}