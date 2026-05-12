<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Enums\Event;
use App\Enums\Resource;
use App\Models\Attempt;
use App\Support\Log\LogActivity;
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
        $this->log($attempt);
        return $attempt;
    }

    protected function attemptNotBanned(Attempt $attempt):bool{
        return !$attempt->isBanned();
    }

    protected function log(Attempt $attempt):void{
        LogActivity::event(
            event:Event::Updated,
            resource:Resource::Attempt,
            context:[
                'attempt_id' => $attempt->id,
                'status' => AttemptStatus::Checked
            ]
        );
    }

   
}