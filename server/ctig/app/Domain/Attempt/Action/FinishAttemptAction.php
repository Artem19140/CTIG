<?php

namespace App\Domain\Attempt\Action;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Domain\Attempt\Guard\AttemptGuard;

class FinishAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard,
        protected ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswers,
        protected CheckPassingThresholdAction $checkPassingThreshold
    ){}
    public function execute(Attempt $attempt):void{
        $this->attemptGuard->ensureNotBanned($attempt);
        //$this->attemptGuard->ensureActive($attempt);

        DB::transaction(function() use($attempt){
            $attempt->finishTimeNow();
            
            $this->zeroEmptyAutoAnswers->execute($attempt);

            if(!$attempt->requiresHumanCheck()){
                $isPassed = $this->checkPassingThreshold->execute($attempt);
                $attempt->status = AttemptStatus::Checked;
                $attempt->checked_at = Carbon::now($attempt->time_zone);
                $attempt->total_mark = $attempt->answers()->sum('mark');
                $attempt->is_passed = $isPassed;
            }
            //Еще end_time_real - у Exam
            $attempt->save();
        });
        
    }
}