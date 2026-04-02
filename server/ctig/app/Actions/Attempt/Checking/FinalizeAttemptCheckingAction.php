<?php

namespace App\Actions\Attempt\Checking;

use App\Enums\AttemptStatus;
use App\Models\Attempt;

class FinalizeAttemptCheckingAction{
    public function execute(Attempt $attempt){
        $attempt->status = AttemptStatus::Checked;
        $attempt->total_mark = $attempt->answers()->sum('mark');
        $isSuccessAttempt = $this->checkPassingThreshold($attempt);
        if($isSuccessAttempt){
            $attempt->is_passed = true;
        }else{
            $attempt->is_passed = false;
        }
        return true;
    }
}