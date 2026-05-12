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
        $attempt->status = AttemptStatus::Checked;
        $attempt->checked_at = Carbon::now();
        $attempt->total_mark = $attempt->answers()->sum('mark');
        $attempt->is_passed = $this->checkPassingThreshold->execute($attempt);
        $attempt->save();
        $this->log($attempt);
        return $attempt;
    }

    protected function log(Attempt $attempt){
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