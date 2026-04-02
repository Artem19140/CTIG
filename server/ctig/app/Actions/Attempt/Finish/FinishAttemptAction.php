<?php

namespace App\Actions\Attempt\Finish;


use App\Actions\Attempt\Finish\ZeroEmptyAutoCheckAnswersAction;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Actions\Attempt\Checking\CheckPassingThresholdAction;
use App\Validation\AttemptValidation;
use Illuminate\Support\Facades\DB;

class FinishAttemptAction{
    public function __construct(
        protected AttemptValidation $attemptValidation,
        protected ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswers,
        protected CheckPassingThresholdAction $checkPassingThreshold
    ){}
    public function execute(Attempt $attempt):void{
        $this->attemptValidation->ensureNotBanned($attempt);
        $this->attemptValidation->ensureActive($attempt);

        DB::transaction(function() use($attempt){
            $attempt->finish();
            
            $this->zeroEmptyAutoAnswers->execute($attempt);

            if(!$attempt->requiresHumanCheck()){
                $isPassed = $this->checkPassingThreshold->execute($attempt);
                $attempt->status = AttemptStatus::Checked;
                $attempt->total_mark = $attempt->answers()->sum('mark');
                $attempt->is_passed = $isPassed;
            }
            $attempt->save();
        });
        
    }
}