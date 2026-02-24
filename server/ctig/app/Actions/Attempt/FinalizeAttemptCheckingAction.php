<?php

use App\Actions\Attempt\CheckPassingThresholdAction;
use App\Enums\AttemptStatus;
use App\Models\Attempt;

class FinalizeAttemptCheckingAction{
    public function __construct(private CheckPassingThresholdAction $checkPassingThreshold){}
    public function execute(Attempt $attempt){
        $attempt->status = AttemptStatus::Checked;
        $totalMarkCount = $attempt->answers()->sum('mark');
        $attempt->total_mark = $totalMarkCount;
        $isSuccessAttempt = $this->checkPassingThreshold->execute($attempt);
        if($isSuccessAttempt){
            $attempt->is_passed = true;
        }else{
            $attempt->is_passed = false;
        }
        return true;
    }
}