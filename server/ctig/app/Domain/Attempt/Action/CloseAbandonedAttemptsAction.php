<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Enums\Event;
use App\Enums\Resource;
use App\Models\Attempt;
use App\Support\Log\LogActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Context;

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
        context(['actor_type' => 'cron', 'actor_id' => null]);
        if ($attempt->finished_at !== null) {
            return;
        }
        $attempt->finished_at = $attempt->last_activity_at;
        if ($attempt->canBeAutomaticallyFinalized()) {
            $this->finilizeAttemptCheckingAction->execute($attempt);
        } else {
            $attempt->status = AttemptStatus::Finished;
            $attempt->save();
            $this->log($attempt);
        }
        
        
    }

    protected function log(Attempt $attempt){
        LogActivity::event(
            event:Event::Updated,
            resource:Resource::Attempt,
            context:[
                'attempt_id' => $attempt->id,
                'status' => AttemptStatus::Finished
            ]
        );
    }
}